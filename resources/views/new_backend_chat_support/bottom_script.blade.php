
	</div>

  <section class="chat-box-wrapper">
    <div class="chatbox-holder" id="chat_box">
        @foreach($chats as $chat)
        @if($chat->status == 'open')
        <div id="chat_{{$chat->user_id}}" class="chatbox group-chat">
          <div class="chatbox-top">
            <div class="chatbox-avatar">
              <a href="#"><img src="{{$chat->user_image}}" /></a>
            </div>
            
            <div class="chat-group-name">
              {{$chat->username}}
            </div>
            <div class="chatbox-icons">
              <label for="chkSettings_{{$chat->user_id}}" onclick="chat_done({{$chat->user_id}})"><i class="fa fa-check bg-success"></i></label><input type="checkbox" id="chkSettings" />
              <div class="settings-popup">
                <ul>
                  <li><a onclick="chat_done({{$chat->user_id}})" href="#">Done</a></li>
                </ul>
              </div>
              <a onclick="hide_chat({{$chat->user_id}})" href="javascript:void(0);"><i class="fa fa-minus"></i></a>
              <a onclick="close_chat({{$chat->user_id}})" href="javascript:void(0);"><i class="fa fa-close"></i></a>       
            </div>      
          </div>
          <div class="chat-messages" id="msg_{{$chat->user_id}}">
          @foreach(json_decode($chat->msgs) as $msg)
          @if($msg->from != $msg->user_id)
             <div class="message-box-holder">
              <div class="message-box">
                {{$msg->message}}
              </div>
            </div>
            @else
            <div class="message-box-holder">
              <div class="message-box message-partner">
              {{$msg->message}}
              </div>
            </div>
            @endif
            @endforeach
          </div>
          <div class="chat-input-holder">
            <textarea id="message_{{$chat->user_id}}" class="chat-input"></textarea>
            <input type="submit" onclick="msg_send({{$chat->user_id}})" value="Send" class="message-send" />
          </div>
        </div>
        @endif
        @endforeach
        <!-- <div class="chatbox group-chat">
          <div class="chatbox-top">
            <div class="chatbox-avatar">
              <a target="_blank" href="#"><img src="https://images.unsplash.com/photo-1633332755192-727a05c4013d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8dXNlcnxlbnwwfHwwfHw%3D&w=1000&q=80" /></a>
            </div>
            
            <div class="chat-group-name">
              <span class="status away"></span>
              Group name comes here
            </div>
            <div class="chatbox-icons">
              <label for="chkSettings"><i class="fa fa-gear"></i></label><input type="checkbox" id="chkSettings" />
              <div class="settings-popup">
                <ul>
                  <li><a href="#">Group members</a></li>
                  <li><a href="#">Add members</a></li>
                  <li><a href="#">Delete members</a></li>
                  <li><a href="#">Leave group</a></li>
                </ul>
              </div>
              <a href="javascript:void(0);"><i class="fa fa-minus"></i></a>
              <a href="javascript:void(0);"><i class="fa fa-close"></i></a>       
            </div>      
          </div>
          
          <div class="chat-messages">
             <div class="message-box-holder">
              <div class="message-box">
                What are you people doing?
              </div>
            </div>
            
            <div class="message-box-holder">
              <div class="message-sender">
                <a href="#">Ben Stiller</a>
               </div>
              <div class="message-box message-partner">
                Hey, nobody's here today. I'm at office alone.
                Hey, nobody's here today. I'm at office alone.
              </div>
            </div>
            
            <div class="message-box-holder">
              <div class="message-box">
                who else is online?
              </div>
            </div>
            
            <div class="message-box-holder">
              <div class="message-sender">
                <a href="#">Chris Jerrico</a>
               </div>
              <div class="message-box message-partner">
                I'm also online. How are you people?
              </div>
            </div>
            
            <div class="message-box-holder">
              <div class="message-box">
                I am fine.
              </div>
            </div>
            
            <div class="message-box-holder">
              <div class="message-sender">
                <a href="#">Rockey</a>
               </div>
              <div class="message-box message-partner">
                I'm also online. How are you people?
              </div>
            </div>
            
            <div class="message-box-holder">
              <div class="message-sender">
                <a href="#">Christina Farzana</a>
               </div>
              <div class="message-box message-partner">
                We are doing fine. I am in.
              </div>
            </div>      
          </div>
          
          <div class="chat-input-holder">
            <textarea class="chat-input"></textarea>
            <input type="submit" value="Send" class="message-send" />
          </div>
          
        </div> -->

      </div>


      <section class="chat-window docked">
        @if($count!=0)
        <div class="chat-header online">
        @else
        <div class="chat-header offline">
        @endif
          <p>Live Chat</p>
          <span class="close"></span>
        </div>
        <div class="chat-body">
          <div class="message-container" id="list">
          <!-- loop -->
          @foreach($chats as $chat)
            <div id="entry_{{$chat->user_id}}" class="message" onclick="open_chat({{$chat->user_id}})">
              <div class="profile">
                <img src="{{$chat->user_image}}" />
              </div>
              @if($chat->token_status=='unsolved' && $chat->status != 'open')
              <div class="message-meta">
                <p style="cursor: pointer;">{{$chat->username}}</p>
                <span id="pop_{{$chat->user_id}}" class="fa fa-info-circle" style="color: red"></span>
              </div>
              @else
              <div class="message-meta">
                <p style="cursor: pointer;">{{$chat->username}}</p>
              </div>
              @endif
            </div>
            @endforeach
          <!-- end loop -->
          </div>
        </div>
        
      </section>
    </section>

</div>
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
  crossorigin="anonymous"
></script>
<script src="{{ asset('assets/js/dashboard_custom.js') }}"></script>
<script src="{{ asset('/js/app.js') }}"></script>
<script>
  <?php header('Access-Control-Allow-Origin: *'); ?>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    Echo.channel('get-msg')
        .listen('SendMessage', (e) => {
          var my_id = "{{auth()->user()->id}}";
          if(e.agent_id == my_id && e.text == "support")
          {
            
            if(document.getElementById("entry_"+e.user_id) === null)
            {
              $('#list').append('<div id="entry_'+e.user_id+'" class="message" onclick="open_chat('+e.user_id+')">'
              +'<div class="profile"><img src="'+e.user_image+'" /></div>'
              +'<div class="message-meta"><p style="cursor: pointer;">'+e.username+'</p> '
              +'<span id="pop_'+e.user_id+'" class="fa fa-info-circle" style="color: red"></span></div></div>');
            }
            else if(document.getElementById("chat_"+e.user_id) !== null)
            {
              $('#msg_'+e.user_id).append('<div class="message-box-holder">'
              +'<div class="message-box message-partner">'+e.msg+'</div></div>');
              var lastMsg = $('#msg_'+e.user_id).find('.message-box-holder').last().offset().top;
              $('#msg_'+e.user_id).animate({scrollTop: lastMsg}, 'slow');
            }
            else
            {
              $('#pop_'+e.user_id).addClass('fa fa-info-circle')
            }
            if($('.chat-header').hasClass('offline'))
            {
              $('.chat-header').removeClass('offline')
              $('.chat-header').addClass('online')
            }
          }
        });
//     $(function(){
//   $('.fa-minus').click(function(){    $(this).closest('.chatbox').toggleClass('chatbox-min');
//   });
//   $('.fa-close').click(function(){
//     $(this).closest('.chatbox').hide();
//   });
// });

function hide_chat(user_id)
{
  $('#chat_'+user_id).toggleClass('chatbox-min');
}

function close_chat(user_id)
{
  $.ajax({
        type: "post",
        url: "/chat/status",
        data: {
            status:"close",
            user_id:user_id
        },
        success: function (response) {
        }
    });
  $('#chat_'+user_id).remove();
}

function chat_done(user_id)
{
  $.ajax({
        type: "post",
        url: "/chat/done",
        data: {
            user_id:user_id
        },
        success: function (response) {
          if(response == 'ok')
          {
            $('#chat_'+user_id).remove();
            if(document.getElementById("entry_"+user_id) !== null)
            {
              $('#entry_'+user_id).remove();
            }
          }
        }
    });
}

function open_chat(us_id)
{
  $('#entry_'+us_id).attr('onclick','');
  var my_id = "{{auth()->user()->id}}"
  if(document.getElementById("chat_"+us_id) !== null)
  {
    $('#chat_'+us_id).remove();
  }
  $('#pop_'+us_id).removeClass('fa fa-info-circle');
  $.ajax({
        type: "post",
        url: "/chat/status",
        data: {
            status:"open",
            user_id:us_id
        },
        success: function (chat) {
          $('#chat_box').append('<div class="chatbox group-chat" id="chat_'+chat.user_id+'">'
          +'<div class="chatbox-top"><div class="chatbox-avatar">'
          +'<a href="#"><img src="'+chat.user_image+'" /></a>'
          +'</div><div class="chat-group-name">'+chat.username+'</div>'
          +'<div class="chatbox-icons"><label for="chkSettings_sj" onclick="chat_done('+chat.user_id+')"><i class="fa fa-check bg-success"></i></label>'
          +'<input type="checkbox" id="chkSettings" /><div class="settings-popup"><ul>'
          +'<li><a href="#">Done</a></li></ul></div>'
          +'<a onclick="hide_chat('+chat.user_id+')" href="javascript:void(0);"><i class="fa fa-minus"></i></a>'
          +'<a onclick="close_chat('+chat.user_id+')" href="javascript:void(0);"><i class="fa fa-close"></i></a></div></div></div>'
          );
          $('#chat_'+chat.user_id).append('<div class="chat-messages" id="msg_'+chat.user_id+'"></div>');
          $.each (JSON.parse(chat.msgs), function (key, msg) {
            if(msg.from == my_id)
            {
              $('#msg_'+chat.user_id).append('<div class="message-box-holder">'
              +'<div class="message-box">'+msg.message+'</div></div>');
            }
            else
            {
              $('#msg_'+chat.user_id).append('<div class="message-box-holder">'
              +'<div class="message-box message-partner">'+msg.message+'</div></div>');
            }
          });
          $('#chat_'+chat.user_id).append('<div class="chat-input-holder">'
          +'<textarea id="message_'+chat.user_id+'" class="chat-input"></textarea>'
          +'<input type="submit" onclick="msg_send('+chat.user_id+')" value="Send" class="message-send" /></div>');
          var lastMsg = $('#msg_'+chat.user_id).find('.message-box-holder').last().offset().top;
	        $('#msg_'+chat.user_id).animate({scrollTop: lastMsg}, 'slow');
          $('#entry_'+chat.user_id).attr('onclick','open_chat('+chat.user_id+')');
        }
    });
}

function msg_send(user_id)
{
  var msg = $('#message_'+user_id).val();
  if(msg != '')
  {
    $('.message-send').prop('disabled', true);
    $.ajax({
        type: "post",
        url: "/send/message",
        data: {
            msg:msg,
            user_id:user_id
        },
        success: function (response) {
          if(response=='ok')
          {
            $('#message_'+user_id).val('');
            $('#msg_'+user_id).append('<div class="message-box-holder">'
            +'<div class="message-box">'+msg+'</div></div>');
            var lastMsg = $('#msg_'+user_id).find('.message-box-holder').last().offset().top;
            $('#msg_'+user_id).animate({scrollTop: lastMsg}, 'slow');
            $('.message-send').prop('disabled', false);
          }
        }
    });
  }
}

</script>
<script>
    $(function(){
  $('.chat-header').click(function(){
    // $(this).toggleClass('offline');
    // $(this).toggleClass('online');
    $('.chat-window').toggleClass('docked');
  });
  
  setInterval(function(){
    $('.progress-indicator').toggleClass('hide');
  },7846);
});
</script>