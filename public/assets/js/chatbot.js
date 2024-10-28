var ip = '';
var data = '';
var count = 0;
jQuery(document).ready(function($) {
	$.getJSON("https://api.ipify.org/?format=json", function(e) {
		ip = e.ip.split('.').join('');
		get_questions();
		get_msgs(ip);
	});
	jQuery(document).on('click', '.iconInner', function(e) {
		jQuery(this).parents('.botIcon').addClass('showBotSubject');
		$("[name='msg']").focus();
		if($('#pop').hasClass('fa fa-circle')){$('#pop').removeClass('fa fa-circle');}
	});

	jQuery(document).on('click', '.closeBtn, .chat_close_icon', function(e) {
		jQuery(this).parents('.botIcon').removeClass('showBotSubject');
		jQuery(this).parents('.botIcon').removeClass('showMessenger');
	});

	jQuery(document).on('submit', '#botSubject', function(e) {
		e.preventDefault();

		jQuery(this).parents('.botIcon').removeClass('showBotSubject');
		jQuery(this).parents('.botIcon').addClass('showMessenger');
	});

	/* Chatboat Code */
	$(document).on("submit", "#messenger", function(e) {
		e.preventDefault();
		var val = $("[name=msg]").val().toLowerCase();
		var mainval = $("[name=msg]").val();
		if(count == 0)
		{
			$("[name='msg']").val("");
			$('.Messages_list').append('<div class="msg user"><span class="avtr"><figure style="background-image: url(https://mrseankumar25.github.io/Sandeep-Kumar-Frontend-Developer-UI-Specialist/images/avatar.png)"></figure></span><span class="responsText">' + mainval + '</span></div>');
			$('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">Click on the questions bellow</span></div>');
			$.each (JSON.parse(data), function (key, d) {
				$('.Messages_list').append('<div onclick="reply('+d.id+')" class="msg" style="cursor:pointer;"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">' + d.question + '</span></div>');
			});
			$('.Messages_list').append('<div onclick="reply('+0+')" class="msg" style="cursor:pointer;"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">click to connect with support</span></div>');
			var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
			$('.Messages').animate({scrollTop: lastMsg}, 'slow');
		}
		else if(mainval!='' && ip!='')
		{

			send_guest_msg(ip,mainval);
			$("[name=msg]").val('');
			nowtime = new Date();
			nowhoue = nowtime.getHours();
			function userMsg(msg) {
				$('.Messages_list').append('<div class="msg user"><span class="avtr"><figure style="background-image: url(https://mrseankumar25.github.io/Sandeep-Kumar-Frontend-Developer-UI-Specialist/images/avatar.png)"></figure></span><span class="responsText">' + mainval + '</span></div>');
			};
			function appendMsg(msg) {
				$('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">' + msg + '</span></div>');
				$("[name='msg']").val("");
			};
			userMsg(mainval);
			var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
			$('.Messages').animate({scrollTop: lastMsg}, 'slow');
		}
	});
	/* Chatboat Code */
});

function send_guest_msg(ip,msg)
{
	$.ajax({
		type: "post",
		url: "/send/guest/message",
		data: {
			ip:ip,
			msg:msg
		},
		success: function (response) {
		}
	});
}

function get_questions()
{
	$.ajax({
		type: "get",
		url: "/get/chatbot/questions",
		async: false,
		success: function (response) {
			data = response;
		}
	});
}

function reply(id)
{
	if(id==0)
	{
		count = 1;
		$('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">Now you are connected with our agent send a msg</span></div>');
	}
	else
	{
		$.each (JSON.parse(data), function (key, d) {
			if(d.id==id)
			{
				$('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">' + d.answer + '</span></div>');
			}
		});
	}
	var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
	$('.Messages').animate({scrollTop: lastMsg}, 'slow');
}

function get_msgs(ip)
{
	$.ajax({
		type: "post",
		url: "/get/guest/msgs",
		async: false,
		data: {
			ip:ip
		},
		success: function (chat) {
			if(chat=='' || chat==null)
			{
			}
			else
			{
				count=1;
				$.each (chat, function (key, ch) {
					if(ch.from==ip)
					{
						$('.Messages_list').append('<div class="msg user"><span class="avtr"><figure style="background-image: url(https://mrseankumar25.github.io/Sandeep-Kumar-Frontend-Developer-UI-Specialist/images/avatar.png)"></figure></span><span class="responsText">'+ch.message+'</span></div>');
					}
					else
					{
						$('.Messages_list').append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">'+ch.message+'</span></div>');
					}
				});
			}
		}
	});
}
