

// JavaScript Document
// $(document).ready(function() {

//     "use strict";

//     $(".hero-form").submit(function(e) {
//         e.preventDefault();        
//         var name = $(".name");
//         var email = $(".email");
//         var phone = $(".phone");
//         var subject = $(".subject");
//         var msg = $(".message");
//         var flag = false;
//         if (name.val() == "") {
//             name.closest(".form-control").addClass("error");
//             name.focus();
//             flag = false;
//             return false;
//         } else {
//             name.closest(".form-control").removeClass("error").addClass("success");
//         } if (email.val() == "") {
//             email.closest(".form-control").addClass("error");
//             email.focus();
//             flag = false;
//             return false;
//         } else {
//             email.closest(".form-control").removeClass("error").addClass("success");
//         } if (phone.val() == "") {
//             phone.closest(".form-control").addClass("error");
//             phone.focus();
//             flag = false;
//             return false;
//         } else {
//             phone.closest(".form-control").removeClass("error").addClass("success");
//           if (subject.val() == "") {
//             subject.closest(".form-control").addClass("error");
//             subject.focus();
//             flag = false;
//             return false;
//         } else {
//             subject.closest(".form-control").removeClass("error").addClass("success");
//         } if (msg.val() == "") {
//             msg.closest(".form-control").addClass("error");
//             msg.focus();
//             flag = false;
//             return false;
//         } else {
//             msg.closest(".form-control").removeClass("error").addClass("success");
//             flag = true;
//         }
    
//     }
//     });
        // var dataString = "name=" + name.val() + "&email=" + email.val() + "&phone=" + phone.val() + "&subject=" + subject.val() + "&msg=" + msg.val();
        // $(".loading").fadeIn("slow").html("Loading...");
        // $.ajax({
        //     type: "POST",
        //     data: dataString,
        //     url: "/contact_us",
        //     cache: false,
        //     success: function (d) {
        //         $(".form-control").removeClass("success");
        //             if(d == 'success') // Message Sent? Show the 'Thank You' message and hide the form
        //                 $('.loading').fadeIn('slow').html('<font color="#00596e">Mail sent Successfully.</font>').delay(3000).fadeOut('slow');
        //                  else
        //                 $('.loading').fadeIn('slow').html('<font color="#ff5607">Mail not sent.</font>').delay(3000).fadeOut('slow');
        //                         }
        // });
        // return false;
    // });
    // $("#reset").on('click', function() {
    //     $(".form-control").removeClass("success").removeClass("error");
    // });
    
// });

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}
function hasCharactersOnly(myString) {
    return /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~0-9]/.test(myString);
}
function isNumber(iKeyCode) {
    // var iKeyCode = (evt.which) ? evt.which : evt.keyCode
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;
    return true;
}
$(function() {
// console.log('jjj')


$(document).ready(function() {
    $('.err_msg').hide();
})
$('#send_msg').click(function() {
    // console.log('function')

    contactname = $('#name').val();
    email = $('#email').val();
    phone = $('#phone').val();
    subject = $('#subject').val();
    msg = $('#message').val();
    // console.log( contactname)
    // console.log(email)
    // console.log(phone)
    // console.log(subject)
    // console.log(msg)

    if (
        contactname != "" &&
        email != "" &&
        phone != "" &&
        subject != "" &&
        msg != "" 

    ) {
            this.disabled = true;
            validated = true;
            // console.log("validate");
         if (contactname != "" && hasCharactersOnly(contactname)) {
            // console.log('error')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#name_err").addClass("d-block");
            $('#name_err').show();
            validated = false;
        } else {
            $("#name_err").removeClass("d-block");
        }
        if (email != "" && !validateEmail(email)) {
            // console.log('error')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#email_err").addClass("d-block");
            $('#email_err').show();
            validated = false;
        } else {
            $("#email_err").removeClass("d-block");
        }
        // if (phone == "") {
        //     $("#phone_err").removeClass("d-block");
        //     $('#phone_err').hide();

        // } else {
        //     $(".invalid-feedback ").css({
        //         "color": "red ",
        //         "font-size": "12px"
        //     });
        //     $("#phone_err").addClass("d-block");
        //     $('#phone_err').show();
        //     validated = false;
        // }
        
        if (phone == "") {
            // console.log('error')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#phone_err").addClass("d-block");
            $('#phone_err').show();
            validated = false;
        } else {
            $("#phone_err").removeClass("d-block");
        }
        if (subject == "") {
            // console.log('error')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#sub_err").addClass("d-block");
            $('#sub_err').show();
            validated = false;
        } else {
            $("#sub_err").removeClass("d-block");
        } if (msg == "" ) {
            // console.log('error')
            $(".invalid-feedback ").css({
                "color": "red ",
                "font-size": "12px"
            });
            $("#msg_err").addClass("d-block");
            $('#msg_err').show();
            validated = false;
        } else {
            $("#msg_err").removeClass("d-block");
        }
       
        if(validated == true){
            console.log('submit')
            $('#frm').submit();
            // this.disabled = true;

        }
        else{
            this.disabled = false;

        }

    } 
    else{
        $("#msg").addClass("d-block");
        $(".invalid-feedback ").css({
            "color": "red ",
            "font-size": "14px"
        });
        this.disabled = false;
    }
 })
})


