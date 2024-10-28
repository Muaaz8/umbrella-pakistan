Echo.channel('updateQueEvent')
    .listen('updateQuePatient', (e) => {
    if(e.message=="update patient que")
    {
        checkSessionStatus();
    }
});

var requested = false;
var docid = $('#doc_id').attr('class');
var session_id = $('#session_id').val();

$(document).ready(function(){
    checkSessionStatus();
});

function checkSessionStatus()
{
    $.ajax({
        type:"POST",
        url:"{{ route('session.status.check') }}",
        data:{
            session_id:session_id
        },
        dataType:'json',
        success:function(data){
            var status=data.data.status;
            var appointment_id=data.data.appointment_id;
            var que_message=data.data.que_message;
            if(appointment_id=="" || appointment_id==null)
            {
                if(status=="paid")
                {
                    $("#loadingButton").css('display','none');
                    $("#invite").css('display','block');
                    $("#farworded").css('display','none');
                }
                else{
                    $("#loadingButton").css('display','none');
                    $("#invite").css('display','none');
                    $("#timer").html(que_message);
                    $("#farworded").css('display','block');
                }
            }else{

                    $("#loadingButton").css('display','none');
                    $("#invite").css('display','none');
                    $("#timer").html(que_message);
                    $("#farworded").css('display','block');

            }

        }
    });
}

function sendInviteClick()
{
    $("#loadingButton").css('display','block');
    $("#invite").css('display','none');
    $.ajax({
        type:"POST",
        url:"{{ route('invite.session') }}",
        data:{
            session_id:session_id
        },
        dataType:'json',
        success:function(data){
            var status=data.data.status;
            var que_message=data.data.que_message;
            if(status=="invitation sent")
            {
                $("#loadingButton").css('display','none');
                $("#farworded").css('display','block');
                $("#timer").html(que_message);
            }
        }
    });
}