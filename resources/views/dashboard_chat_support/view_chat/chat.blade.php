@extends('layouts.dashboard_chat_support')
@section('meta_tags')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" href="{{ asset('asset_frontend/images/logo.ico') }}" type="image/x-icon">
@endsection

@section('page_title')
    <title>UHCS - Chat</title>
@endsection

@section('top_import_file')
@endsection

@section('bottom_import_file')
<script>
    $(document).ready(function(){
        var lastMsg = $('.msger-chat').find('.msg').last().offset().top;
	    $('.msger-chat').animate({scrollTop: lastMsg}, 'slow');
    });
</script>
@endsection

@section('content')
<div class="dashboard-content">
            <div class="container-fluid">
                <div class="p-4">
                
                <section class="msger">
                    <header class="msger-header">
                    <div class="msger-header-title">
                    <div class="d-flex align-items-center"><img style="border-radius:100%" src="{{$message->user_image}}" alt=""><p class="ms-2 fw-bold">{{$message->username}}</p></div>
                        
                      </div>
                      <div class="msger-header-options">

                      </div>
                    </header>
                    <main class="msger-chat">
                    @foreach($messages as $msg)
                    @if($msg->to == auth()->user()->id)
                      <div class="msg left-msg">
                        <div
                         
                         
                        ><img class="right__img" src="{{$msg->user_image}}" alt=""></div>
                  
                        <div class="msg-bubble">
                          <div class="msg-info">
                            <div class="msg-info-name">{{$msg->username}}</div>
                            <div class="msg-info-time">{{$msg->created_at['date']}} {{$msg->created_at['time']}}</div>
                          </div>
                  
                          <div class="msg-text">
                          {{$msg->message}}
                          </div>
                        </div>
                      </div>
                      @else
                      <div class="msg right-msg">
                  
                        <div class="msg-bubble">
                          <div class="msg-info">
                            <div class="msg-info-name">You</div>
                            <div class="msg-info-time">{{$msg->created_at['date']}} {{$msg->created_at['time']}}</div>
                          </div>
                  
                          <div class="msg-text">
                          {{$msg->message}}
                          </div>
                        </div>
                      </div>
                      @endif
                      @endforeach
                    </main>
                  </section>
              </div>
              </div>
        </div>
@endsection