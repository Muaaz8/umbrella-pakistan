function myFunction(id){
    $('#load_refill').html('');
    if(id!=null || id!=''){
        var sno=1;
        $('#load_refill').append(
        '<input id="pr_id" name="id" value="'+id+'" hidden="">'
            );
        $('#refill-modal').modal('show');
    }

}


$(function() {
    $('input[name="dates"]').daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear',
            opens: 'left'
        }

    }, function(start, end, label) {
      console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
    $('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="dates"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
  });


  function runSpeechRecognition() {

    // get action element reference
    var action = document.getElementById("start-record-btn");
    // new speech recognition object
    var SpeechRecognition = SpeechRecognition || webkitSpeechRecognition;
    var recognition = new SpeechRecognition();

    // This runs when the speech recognition service starts
    recognition.onstart = function() {
        $('#start-record-btn').css("background-color","#2185d0");
        action.innerHTML = "listening, please speak...";
    };
    recognition.onspeechend = function() {
        action.innerHTML = "stopped listening, hope you are done...";
        recognition.stop();
    }
    // This runs when the speech recognition service returns result
    recognition.onresult = function(event) {
        var previousData=$('#note-textarea').val();
        var transcript = event.results[0][0].transcript;
        $('#start-record-btn').css("background-color","red");
        $('#note-textarea').val(previousData+" "+transcript);

    };
     // start recognition
     recognition.start();
}