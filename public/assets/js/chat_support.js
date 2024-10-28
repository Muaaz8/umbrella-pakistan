jQuery(document).ready(function(s)
{
    jQuery(document).on("click",".iconInner",function(e){
        jQuery(this).parents(".botIcon").addClass("showBotSubject"),s("[name='msg']").focus();
        if($('#pop').hasClass('fa fa-circle')){$('#pop').removeClass('fa fa-circle');}
    }),
    jQuery(document).on("click",".closeBtn, .chat_close_icon",function(s){
        jQuery(this).parents(".botIcon").removeClass("showBotSubject"),
        jQuery(this).parents(".botIcon").removeClass("showMessenger")
    }),
    jQuery(document).on("submit","#botSubject",function(s){
        s.preventDefault(),jQuery(this).parents(".botIcon").removeClass("showBotSubject"),
        jQuery(this).parents(".botIcon").addClass("showMessenger")}),
        s(document).on("submit","#messenger",function(e){
            e.preventDefault();
            send_msg();
        });
});
function send_msg()
{
    var msg = $('#msg').val().toLowerCase();
    if(msg != '')
    {
        $.ajax({
            type: "post",
            url: "/send/message",
            data: {
                msg:msg
            },
            success: function (response) {
                $(".Messages_list").append('<div class="msg user"><span class="avtr"><figure style="background-image: url('+response.user_image+')"></figure></span><span class="responsText">'+msg+"</span></div>");
                var lastMsg = $('.Messages_list').find('.msg').last().offset().top;
	            $('.Messages').animate({scrollTop: lastMsg}, 'slow');
            }
        });
    }
    text = '';
    $('#msg').val(text);
    //$(".Messages_list").append('<div class="msg"><span class="avtr"><figure style="background-image: url(https://demo.umbrellamd-video.com/assets/images/logo.png)"></figure></span><span class="responsText">'+t+"</span></div>");
}
