
$(function() {
// console.log('jjj')


$(document).ready(function() {
    $('.err_msg').hide();
})
$('#pay_submit').click(function() {
    name = $('#name').val();
    card = $('#card').val();
    cvc = $('#cvc ').val();
    zipcode = $('#zipcode').val();
    exp = $('#exp').val();
    state = $('#state ').val();
    city = $('#city ').val();
    if (
        name != "" &&
        card != "" &&
        cvc != "" &&
        exp != "" &&
        state != "" &&
        city != "" &&
        zipcode != ""

    ) {
            validated = true;
         if (name != "" && hasCharactersOnly(name)) {
            // console.log('error')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#full_name_err').show();
            validated = false;
        } else {
            $("#name_err").removeClass("d-block");
        }
        if (card != "" && isNumber(card)) {
            $("#name_err").removeClass("d-block");
            $('#card_err').hide();

        } else {
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#card_err').show();
            validated = false;


        }

        if (cvc != "" && isNumber(cvc)) {
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#cvc_err').show();
            validated = false;

        } else {
            $("#name_err").removeClass("d-block");
        }
        if (exp == "") {
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#exp_err').show();
            validated = false;

        } else {
            $("#name_err").removeClass("d-block");
        }
        if (city == "") {
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#city_err').show();
            validated = false;

        } else {
            $("#name_err").removeClass("d-block");
        }
        if (state == "") {
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#state_err').show();
             validated = false;

        } else {
            $("#name_err").removeClass("d-block");
        }
        if (zipcode != "" && isNumber(zipcode)) {
            console.log('zipcode')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#zip_err').show();
            validated = false;
        } else {
            $("#name_err").removeClass("d-block");
        }
        if(validated == true){
            console.log('submit')
            $('#payment-form').submit();
        }
        else{
            this.disabled = false;

        }

    } else{
        $("#msg").addClass("d-block");
        $(".invalid-feedback ").css({
            "color": "red ",
            "font-size": "14px"
        });
        this.disabled = false;
    }

})
$(document).ready(function(){
    date=new Date();
//    " 2018-03"
    month = date.getMonth()+1;
    console.log(month);
    year = date.getFullYear();
    if(month<10) month='0'+month;
    exp_date = year + "-" + month;
    $('#date').attr('min',exp_date);
});
})
