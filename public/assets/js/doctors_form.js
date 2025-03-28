var btn = $('#top-scroll-button');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '200');
});





var btn1 = $('#fixed-chat');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn1.addClass('show');
  } else {
    btn1.removeClass('show');
  }
});
//  ********SIGNUP FORM STARTS************
function showPassword() {
  var x = document.getElementById("password");
  var y = document.getElementById("password_confirmation");

$(this).toggleClass("fa-eye fa-eye-slash");
  if (x.type === "password") {
    x.type = "text";
    $(".eye__pass_").addClass("fa-eye");
    $(".eye__pass_").removeClass("fa-eye-slash");
    // y.type = "text";
  } else {
    x.type = "password";
    $(".eye__pass_").addClass("fa-eye-slash");
    $(".eye__pass_").removeClass("fa-eye");
    // y.type = "password";
  }
  if (y.type === "password") {
    y.type = "text";
    $(".eye__pass_").addClass("fa-eye");
    $(".eye__pass_").removeClass("fa-eye-slash");
  } else {
    y.type = "password";
    $(".eye__pass_").addClass("fa-eye-slash");
    $(".eye__pass_").removeClass("fa-eye");
  }

}

$(document).ready(function(){

    $('.select2').select2({
        closeOnSelect: false
    });

    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;

    setProgressBar(current);

    $('input[name="rep_radio"]').click(function(){
        var demovalue = $(this).val();
        $('#typeError').text('');
        $("div.myDiv").hide();
        $("#"+demovalue).show();
    });


    $(".firstNext").click(function(){
        $(window).scrollTop(350);


        var email=$('#email').val();
        // var checker=checkEmail(email);
        email_flag = emailExists(email);
        if(email_flag==1)
        {
            $('#email_error').text('this email already register');
            $('#email').addClass('border-danger');
            return false;
        }
        else{
            $('#email_error').text('');
            $('#email').removeClass('border-danger');
        }
        var emailRegex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;




        if(!emailRegex.test(email))
        {
            $('#email_error').text('invalid email');
            $('#email').addClass('border-danger');
            return false;
        }
        else{
            $('#email_error').text('');
            $('#email').removeClass('border-danger');
        }


        var username=$('#username').val();

        username_flag = usernameExists(username);

        if(username_flag==1)
        {
            $('#username_error').text('this username already taken');
            $('#username').addClass('border-danger');
            return false;
        }
        else{
            $('#username_error').text('');
            $('#username').removeClass('border-danger');
        }

        var userNameRegex =new RegExp("^[a-zA-Z0-9]+$");
        if(!userNameRegex.test(username))
        {
            $('#username_error').text('invalid username only alphanumeric allowed e.g (john123)');
            $('#username').addClass('border-danger');
            return false;
        }else{
            $('#username_error').text('');
            $('#username').removeClass('border-danger');
        }

        var password=$('#password').val();

        if(password.length<8)
        {
            $('#password_error').text('Password should be 8 characters long');
            $('#password').addClass('border-danger');
            return false;

        }
        else{
            $('#password_error').text('');
            $('#password').removeClass('border-danger');
            var specialChar = /[!@#$%^&*()_+\-=]/g;
            var lowerCaseLetters = /[a-z]/g;
            var upperCaseLetters = /[A-Z]/g;
            var numbers = /[0-9]/g;

            if(!password.match(specialChar))
            {
                $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                $('#password').addClass('border-danger');
                return false;
            }else{

                $('#password_error').text('');
                $('#password').removeClass('border-danger');
            }

            if(!password.match(lowerCaseLetters))
            {
                $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                $('#password').addClass('border-danger');
                return false;
            }else{

                $('#password_error').text('');
                $('#password').removeClass('border-danger');
            }

            if(!password.match(upperCaseLetters))
            {

                $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                $('#password').addClass('border-danger');
                return false;
            }else{

                $('#password_error').text('');
                $('#password').removeClass('border-danger');
            }

            if(!password.match(numbers))
            {
                $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                $('#password').addClass('border-danger');
                return false;
            }
            else{
                $('#password_error').text('');
                $('#password').removeClass('border-danger');
            }
        }

        var password_confirmation=$('#password_confirmation').val();
        if(password_confirmation!=password)
        {
            $('#confirm_password_error').text('Confirm password does not match.');
            $('#password_confirmation').addClass('border-danger');
            return false;
        }
        else{
            $('#confirm_password_error').text('');
            $('#password_confirmation').removeClass('border-danger');
        }



        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;
                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({'opacity': opacity});
            },
            duration: 500
        });
        setProgressBar(++current);
    });









    $(".secondNext").click(function(){
        $(window).scrollTop(350);


            var nameRegex = /^[a-zA-Z]+ ?$/;
            var name=$('#name').val();
            if(!nameRegex.test(name))
            {
                $('#fname_error').text('please enter your first name e.g (john)');
                $('#name').addClass('border-danger');
                return false;
            }else{
                $('#fname_error').text('');
                $('#name').removeClass('border-danger');
            }

            var lnameRegex = /^[a-zA-Z]+ ?$/;
            var last_name=$('#last_name').val();
            if(!lnameRegex.test(last_name))
            {
                $('#lname_error').text('please enter your last name e.g (kent)');
                $('#last_name').addClass('border-danger');
                return false;
            }else{
                $('#lname_error').text('');
                $('#last_name').removeClass('border-danger');
            }

            var gender=$('#gender').val();
            if(gender=="")
            {
                $('#gender_error').text('Please choose gender');
                $('#gender').addClass('border-danger');
                return false;
            }else{
                $('#gender_error').text('');
                $('#gender').removeClass('border-danger');
            }

            var date_of_birth=$('#date_of_birth').val();
            if(date_of_birth=='')
            {
                $('#bod_error').text('Please enter your DOB e.g(mm-dd-yyyy)');
                $('#date_of_birth').addClass('border-danger');
                return false;
            }else{
                $('#bod_error').text('');
                $('#date_of_birth').removeClass('border-danger');
                var [month,day,year] = date_of_birth.split("-")
                var date_of_birth = new Date(year,month-1,day);
                var today = new Date();
                var age = Math.floor((today-date_of_birth) / (365.25 * 24 * 60 * 60 * 1000));
                // console.log(date_of_birth+" "+today+" "+age);
                if(month > 12 || day > 31){
                    $('#bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                    $('#date_of_birth').addClass('border-danger');
                    return false;
                }
                if(age<18)
                {
                    $('#bod_error').text('You must be 18 years old');
                    $('#date_of_birth').addClass('border-danger');
                    return false;
                }else if(age>100){
                    $('#bod_error').text('You must be less than 100 years old');
                    $('#date_of_birth').addClass('border-danger');
                    return false;
                }else{
                    $('#bod_error').text('');
                    $('#date_of_birth').removeClass('border-danger');
                }
            }


            var tel=$('#phone_number').val();

            if(tel.length>10 || tel.length<9)
            {
                $('#phone_error').text('Please put a valid number');
                $('#phone_number').addClass('border-danger');
                return false;
            }
            else{
                $('#phone_error').text('');
                $('#phone_number').removeClass('border-danger');
                var phoneRegex =new RegExp('^(?!0|1)[0-9]{1,45}$');

                if(!phoneRegex.test(tel))
                {
                    $('#phone_error').text('Phone field contain only number and cannot start with 0 or 1');
                    $('#phone_number').addClass('border-danger');
                    return false;
                }else{
                    $('#phone_error').text('');
                    $('#phone_number').removeClass('border-danger');
                    var same = duplication(tel);
                    if (same){
                        $('#phone_error').text('Phone Number cannot have 7 same Numbers');
                        $('#phone_number').addClass('border-danger');
                        return false;
                    }else{
                        $('#phone_error').text('');
                        $('#phone_number').removeClass('border-danger');
                    }
                }
            }

            var zip_code=$('#zip_code').val();
            if(zip_code.length=='')
            {
                $('#zipcode_error').text('Please put a valid zipcode');
                $('#zip_code').addClass('border-danger');
                return false;
            }
            else{
                $('#zipcode_error').text('');
                $('#zip_code').removeClass('border-danger');
            }


            var state=$('#state').val();
            if(state.length=='')
            {
                $('#state_error').text('Please select state');
                $('#state').addClass('border-danger');
                return false;
            }
            else{
                $('#state_error').text('');
                $('#state').removeClass('border-danger');
            }

            var city=$('#city').val();
            if(city.length=='')
            {
                $('#city_error').text('Please select city');
                $('#city').addClass('border-danger');
                return false;
            }
            else{
                $('#city_error').text('');
                $('#city').removeClass('border-danger');
            }


            // var appartment=$('#appartment').val();
            // if(appartment.length=='')
            // {
            //     $('#appartment_error').text('Please insert your street/appartment address');
            //     $('#appartment').addClass('border-danger');
            //     return false;
            // }
            // else if (appartment.length>300){
            //     $('#appartment_error').text('Character limit is 300');
            //     $('#appartment').addClass('border-danger');
            //     return false;
            // }
            // else{
            //     $('#appartment_error').text('');
            //     $('#appartment').removeClass('border-danger');
            // }

            var address=$('#address').val();
            if(address.length=='')
            {
                $('#address_error').text('Please insert your address');
                $('#address').addClass('border-danger');
                return false;
            }
            else if (address.length>300){
                $('#address_error').text('Character limit is 300');
                $('#address').addClass('border-danger');
                return false;
            }
            else{
                $('#address_error').text('');
                $('#address').removeClass('border-danger');
            }



            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({opacity: 0}, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({'opacity': opacity});
                },
                duration: 500
            });
            setProgressBar(++current);


    });
    // $('#id_front_side').bind('change', function() {
    //     var a=(this.files[0].size);
    //     if(a > 4000000) {
    //         $('#id_front_side').val('');
    //         $('#id_front_side_error').text('File is greater than 3MB');
    //         $('#id_front_side').addClass('border-danger');
    //     }else{
    //         $('#id_front_side_error').text('');
    //         $('#id_front_side').removeClass('border-danger');

    //     };
    // });
    // $('#id_back_side').bind('change', function() {
    //     var a=(this.files[0].size);
    //     if(a > 4000000) {
    //         $('#id_back_side').val('');
    //         $('#id_back_side_error').text('File is greater than 3MB');
    //         $('#id_back_side').addClass('border-danger');
    //     }else{
    //         $('#id_back_side_error').text('');
    //         $('#id_back_side').removeClass('border-danger');

    //     };
    // });


    $(".thirdNext").click(function(){
        $(window).scrollTop(350);

        var npi=$('#npi').val();
        exist = nipExists(npi);
        if(npi.length<3)
        {
            $('#npi_error').text('Please put a valid NPI');
            $('#npi').addClass('border-danger');
            return false;
        }
        else if(exist==1)
        {
            $('#npi_error').text('PMDC number already exist');
            $('#npi').addClass('border-danger');
            return false;
        }
        else{
            $('#npi_error').text('');
            $('#npi').removeClass('border-danger');
            // var npiRegex =new RegExp("^[a-zA-Z0-9]*$");
            // //var npiRegex =new RegExp('^[0-9]{1,45}$');

            // if(!npiRegex.test(npi))
            // {
            //     $('#npi_error').text('NPI field contain only number');
            //     $('#npi').addClass('border-danger');
            //     return false;
            // }else{
                // $('#npi_error').text('');
                // $('#npi').removeClass('border-danger');
            // }
        }

        // var upin=$('#upin').val();
        // if(upin.length!=6)
        // {
        //     $('#upin_error').text('Please put a valid UPIN');
        //     $('#upin').addClass('border-danger');
        //     return false;
        // }
        // else{
        //     $('#upin_error').text('');
        //     $('#upin').removeClass('border-danger');
        //     var upinRegex =new RegExp("^[a-zA-Z0-9]*$");

        //     if(!upinRegex.test(upin))
        //     {
        //         $('#upin_error').text('UPIN field contain only number');
        //         $('#upin').addClass('border-danger');
        //         return false;
        //     }else{
        //         $('#upin_error').text('');
        //         $('#upin').removeClass('border-danger');
        //     }
        // }

        var specializations=$('#specializations').val();
        if(specializations=='')
        {
            $('#specializations_error').text('Please select your specialization');
            $('#specializations').addClass('border-danger');
            return false;
        }
        else{
            $('#specializations_error').text('');
            $('#specializations').removeClass('border-danger');
        }

        var licensed_states=$('#licensed_states').val();
        if(licensed_states=='')
        {
            $('#licensed_state_error').text('Please select your licensed state');
            $('#licensed_states').addClass('border-danger');
            return false;
        }
        else{
            $('#licensed_state_error').text('');
            $('#licensed_states').removeClass('border-danger');
        }

        var ext = $('#id_front_side').val().split('.').pop().toLowerCase();
        if(ext!='')
        {
            if($.inArray(ext, ['png','jpg','jpeg']) == -1)
            {
                $('#id_front_side_error').text('Please choose valid file e.g (png,jpg,jpeg)');
                $('#id_front_side').addClass('border-danger');
                return false;
            }
            else
            {
                $('#id_front_side_error').text('');
                $('#id_front_side').removeClass('border-danger');

            }
        }else{
            $('#id_front_side_error').text('');
            $('#id_front_side').removeClass('border-danger');
        }

        var ext = $('#id_back_side').val().split('.').pop().toLowerCase();
        if(ext!='')
        {
            if($.inArray(ext, ['png','jpg','jpeg']) == -1)
            {
                $('#id_back_side_error').text('Please choose valid file e.g (png,jpg,jpeg)');
                $('#id_back_side').addClass('border-danger');
                return false;
            }
            else
            {
                $('#id_back_side_error').text('');
                $('#id_back_side').removeClass('border-danger');

            }
        }else{
            $('#id_back_side_error').text('');
            $('#id_back_side').removeClass('border-danger');
         }

        var ext = $('#profile_pic').val().split('.').pop().toLowerCase();
        if(ext!='')
        {
            if($.inArray(ext, ['png','jpg','jpeg']) == -1)
            {
                $('#profile_pic_error').text('Please choose valid file e.g (png,jpg,jpeg)');
                $('#profile_pic').addClass('border-danger');
                return false;
            }
            else
            {
                $('#profile_pic_error').text('');
                $('#profile_pic').removeClass('border-danger');

            }
        }else{
            $('#id_back_side_error').text('');
            $('#id_back_side').removeClass('border-danger');
         }


        // var ext = $('#upload_record').val().split('.').pop().toLowerCase();
        // if(ext!='')
        // {
        //     if($.inArray(ext, ['pdf','png','jpg','jpeg']) == -1)
        //     {
        //         $('#file_error').text('Please choose valid file e.g (pdf,png,jpg,jpeg)');
        //         $('#upload_record').addClass('border-danger');
        //         return false;
        //     }
        //     else
        //     {
        //         $('#file_error').text('');
        //         $('#upload_record').removeClass('border-danger');

        //     }
        // }
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({'opacity': opacity});
            },
            duration: 500
        });
        setProgressBar(++current);
    });




    // $("#term").change(function() {
    //    var signature = $("#signature").val();
    //     if(this.checked) {
    //         var cnv = document.getElementById('sig-canvas');
    //         $('#term').val('1');
    //         if(!isCanvasEmpty(cnv))
    //         {
    //             $('.forthNext').prop("disabled", false);
    //             $('.forthNext').css("background", '#08295a');
    //             $('#error').html('');
    //             $("#btnSave").click();

    //         }else{
    //             $('.forthNext').css("background", '#a9b9d0');
    //             $('.forthNext').prop("disabled", true);
    //             $('#error').html('');
    //         }
    //     }
    //     else{
    //         $('.forthNext').css("background", '#a9b9d0');
    //         $('.forthNext').prop("disabled", true);
    //         $('#term').val('0');
    //     }
    // });


    $("#zip_code").keyup(function(){
        var zip = $("#zip_code").val();
        var length = $("#zip_code").val().length;
        if(length >= 5) {
            $('#state').html('<option value="">Select State</option>');
            $('#city').html('<option value="">Select City</option>');
            $.ajax({
                type: "POST",
                url: "/get_states_cities",
                data: {
                    zip: zip,
                },
                success: function(data) {
                    if (data == ""){
                        $('#zipcode_error').text('Please enter a valid zipcode');
                        $('#zip_code').addClass('border-danger');
                        return false;
                    } else {
                        $('#zipcode_error').text('');
                        $('#zip_code').removeClass('border-danger');
                        $('#state').html('<option value="'+data.state_id+'">'+data.state+'</option>');
                        $('#city').html('<option value="'+data.city_id+'">'+data.city+'</option>');
                    }
                    // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                },

                // success: function(data) {
                //     $('#state').html('<option value="">Select State</option>');
                //     $('#city').html('<option value="">Select City</option>');
                //     if (data.country_id == ""){
                //         $('#zipcode_error').text('Please enter a valid zipcode');
                //         $('#zip_code').addClass('border-danger');
                //         return false;
                //     }
                //     else {
                //         $('#zipcode_error').text('');
                //         $('#country').val(data.country_id);
                //         $('#zip_code').removeClass('border-danger');
                //         $('#state').html('<option value="'+data.state_id+'">'+data.state_name+'</option>');
                //         $.each(data.all_citys, function(key, value) {
                //             $('#city').append('<option value="'+value.id+'">'+value.name+'</option>');
                //         });

                //     }
                // },
            });
        }
    });
    $("#rep_zip_code").keyup(function(){
        var zip = $("#rep_zip_code").val();
        var length = $("#rep_zip_code").val().length;
        if(length >= 5) {
            $.ajax({
                type: "POST",
                url: "/getCityStateByZipCode",
                data: {
                    zip: zip,
                },
                success: function(data) {
                    if (data.country_id == "") {
                        $('.zipcode_error').text('Please enter a valid zipcode');
                        $('.zip_code').addClass('border-danger');
                        return false;
                    } else {
                        $(".zipCodeErrCheckout").hide();
                        $.ajax({
                            type: "POST",
                            url: "/get_states_cities",
                            data: {
                                id: data.state_id,
                                city_id: data.city_id,
                            },
                            success: function(resp) {
                                if (resp.count > 0) {
                                    $('.state').html('<option value="'+resp.single.id+'">'+resp.single.text+'</option>');
                                    $('.city').html('<option value="'+resp.city.city_id+'">'+resp.city.city_name+'</option>');
                                } else {
                                    $(".zipCodeErrCheckout").show();
                                }
                            },
                        });
                    }
                    // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                },
            });
            // $.ajax({
            //     type: "POST",
            //     url: "/getDataByZipCode",
            //     data: {
            //         zip: zip,
            //     },
            //     success: function(data) {
            //         $('#rep_state').html('<option value="">Select State</option>');
            //         $('#rep_city').html('<option value="">Select City</option>');
            //         if (data.country_id == ""){
            //             $('#rep_zipcode_error').text('Please enter a valid zipcode');
            //             $('#rep_zip_code').addClass('border-danger');
            //             return false;
            //         }
            //         else {
            //             $('#zipcode_error').text('');
            //             $('#rep_country').val(data.country_id);
            //             $('#rep_zip_code').removeClass('border-danger');
            //             $('#rep_state').html('<option value="'+data.state_id+'">'+data.state_name+'</option>');
            //             $.each(data.all_citys, function(key, value) {
            //                 $('#rep_city').append('<option value="'+value.id+'">'+value.name+'</option>');
            //             });

            //         }
            //     },
            // });
        }
    });


    $(".previous").click(function(){
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({'opacity': opacity});
            },
            duration: 500
        });
        setProgressBar(--current);
    });

    function setProgressBar(curStep){
        var percent = parseFloat(100 / steps) * curStep;
        percent = percent.toFixed();
        $(".progress-bar")
          .css("width",percent+"%")
    }

    // $(".submit").click(function(){
    //     return false;
    // })

    });

    var onloadCallback = function() {
        grecaptcha.render('google_recaptcha', {
            'sitekey' : '6LctFXkqAAAAAHG3mAMi56uxbdOJ3iOjAKXhyeyW'
          });
    };

    $('#msform').submit(function(e)
    {
        e.preventDefault();
        var formData = new FormData(this);
        var rcres = grecaptcha.getResponse();
        if(rcres.length)
        {
            $.ajax({
                type:'POST',
                url: "/register",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:()=>{
                    current_fs = $('.forthNext').parent();
                    next_fs = $('.forthNext').parent().next();
                    // Add Class Active
                    $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                    // show the next fieldset
                    next_fs.show();
                    //hide the current fieldset with style
                    current_fs.animate({opacity: 0}, {
                        step: function(now) {
                            // for making fielset appear animation
                            opacity = 1 - now;
                            current_fs.css({
                                'display': 'none',
                                'position': 'relative'
                            });
                            next_fs.css({'opacity': opacity});
                        },
                        duration: 500
                    });
                    var percent = parseFloat(100 / 5) * 5;
                    percent = percent.toFixed();
                    $(".progress-bar").css("width",percent+"%");
                    $('#loader').css('visibility','visible');
                    $('#loader').css('display','block');
                    $('#success_load').css('visibility','hidden');
                    $('#success_load').css('display','none');
                    $(window).scrollTop(350);
                },
                success: (data) =>
                {
                    location.href="/home";
                },
                complete: function(data)
                {
                    $('#loader').css('visibility','hidden');
                    $('#loader').css('display','none');
                    $('#success_load').css('visibility','visible');
                    $('#success_load').css('display','block');
                }
            });
        }
        else
        {
            alert('Please verify reCAPTCHA');
            return false;
        }
    });
    function isCanvasEmpty(cnv) {
        const blank = document.createElement('canvas');

        blank.width = cnv.width;
        blank.height = cnv.height;

        return cnv.toDataURL() === blank.toDataURL();
    }

    function emailExists(email) {
        var response = null;
        $.ajax({
            type: "POST",
            url: "/verify_email_unique",
            data: {
                email: email,
            },
            async: false,
            success: function(data) {
                response = data;
            },
        });
        return response;
    }

    function nipExists(nip_number) {
        var response = null;
        $.ajax({
            type: "POST",
            url: "/verify_nip_unique",
            data: {
                nip_number: nip_number,
            },
            async: false,
            success: function(data) {
                response = data;
            },
        });
        return response;
    }

    function duplication(value)
    {
        for (i = 0; i < value.length; i++) {
            count = 1;
            for (j = i + 1; j < value.length ; j++) {
                if (value[i] == value[j] && value[i] != ' ') {
                    count++;
                    value[j] = '0';
                }
            }
            if (count >= 7) {
                return true;
            }else {
                return false;
            }
        }
    }

    function usernameExists(username) {
        var response = null;
        $.ajax({
            type: "POST",
            url: "/verify_username_unique",
            data: {
                username: username,
            },
            async: false,
            success: function(data) {
                response = data;
            },
        });
        return response;
    }

    $(function () {
        $('#sig-canvas').sketch({
            defaultSize:10
        });
    });

    $("#btnSave").bind("click", function () {
        var cnv = document.getElementById('sig-canvas');
        var base64 = $('#sig-canvas')[0].toDataURL();
        if(isCanvasEmpty(cnv))
        {
            $('#sign_error').text('Please Sign and Save it');
            $('#sig-canvas').addClass('border-danger');
        }
        else
        {
            $('#sign_error').text('');
            $('#sig-canvas').removeClass('border-danger');
            $('#reload').html(
                '<img alt="" src="'+base64+'" style = "border:1px solid #ccc" />'
            );
            $("#signature").val(base64);
        }
        // if(document.getElementById('term').checked && !isCanvasEmpty(cnv))
        // {
        //     $('#term').val('1');
        //     $('.forthNext').prop("disabled", false);
        //     $('.forthNext').css("background", '#08295a');
        //     $('#error').html('');
        // }
        // else{
        //     $('#error').html('<p style="color:red;">Click on the Retake Signature button then Sign the signature pad and Check Terms and Conditions </p><br>');
        //     // $('.forthNext').css("background", '#a9b9d0');
        //     // $('.forthNext').prop("disabled", true);
        //     $('#term').val('0');
        // }
    });
    $('#clearBtn').click(function(){
        $('#error').html('');
        $("#signature").val('');
        $('#reload').html(
            '<canvas id="sig-canvas" width="620" height="160"></canvas>'
        );
        $('#sig-canvas').sketch({
            defaultSize:10
        });
        // $('.forthNext').prop("disabled", true);
        // $('.forthNext').css("background", '#a9b9d0');
    });
    //$(window).scrollTop(350);
    $('#doc_reg_form').submit(function(e)
    {
        e.preventDefault();
        var formData = new FormData(this);
        var rcres = grecaptcha.getResponse();
        if(rcres.length)
        {
            $.ajax({
                type:'POST',
                url: "/register",
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:()=>{
                    var nameRegex = /^[a-zA-Z]+ ?$/;
                    var name=$('#name').val();
                    if(!nameRegex.test(name))
                    {
                        $('#fname_error').text('please enter your first name e.g (john)');
                        $('#name').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }else{
                        $('#fname_error').text('');
                        $('#name').removeClass('border-danger');
                    }

                    var lnameRegex = /^[a-zA-Z]+ ?$/;
                    var last_name=$('#last_name').val();
                    if(!lnameRegex.test(last_name))
                    {
                        $('#lname_error').text('please enter your last name e.g (kent)');
                        $('#last_name').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }else{
                        $('#lname_error').text('');
                        $('#last_name').removeClass('border-danger');
                    }

                    var gender=$('#gender').val();
                    if(gender=="")
                    {
                        $('#gender_error').text('Please choose gender');
                        $('#gender').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }else{
                        $('#gender_error').text('');
                        $('#gender').removeClass('border-danger');
                    }

                    var date_of_birth=$('#date_of_birth').val();
                    if(date_of_birth=='')
                    {
                        $('#bod_error').text('Please enter your DOB e.g(mm-dd-yyyy)');
                        $('#date_of_birth').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }else{
                        $('#bod_error').text('');
                        $('#date_of_birth').removeClass('border-danger');
                        var [month,day,year] = date_of_birth.split("-")
                        var date_of_birth = new Date(year,month-1,day);
                        var today = new Date();
                        var age = Math.floor((today-date_of_birth) / (365.25 * 24 * 60 * 60 * 1000));
                        // console.log(date_of_birth+" "+today+" "+age);
                        if(month > 12 || day > 31){
                            $('#bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                            $('#date_of_birth').addClass('border-danger');
                            $(window).scrollTop(350);
                            return false;
                        }
                        if(age<18)
                        {
                            $('#bod_error').text('You must be 18 years old');
                            $('#date_of_birth').addClass('border-danger');
                            $(window).scrollTop(350);
                            return false;
                        }else if(age>100){
                            $('#bod_error').text('You must be less than 100 years old');
                            $('#date_of_birth').addClass('border-danger');
                            $(window).scrollTop(350);
                            return false;
                        }else{
                            $('#bod_error').text('');
                            $('#date_of_birth').removeClass('border-danger');
                        }
                    }

                    var tel=$('#phone_number').val();

                    if(tel.length>10 || tel.length<9)
                    {
                        $('#phone_error').text('Please put a valid number');
                        $('#phone_number').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else{
                        $('#phone_error').text('');
                        $('#phone_number').removeClass('border-danger');
                        var phoneRegex =new RegExp('^(?!0|1)[0-9]{1,45}$');

                        if(!phoneRegex.test(tel))
                        {
                            $('#phone_error').text('Phone field contain only number and cannot start with 0 or 1');
                            $('#phone_number').addClass('border-danger');
                            $(window).scrollTop(350);
                            return false;
                        }else{
                            $('#phone_error').text('');
                            $('#phone_number').removeClass('border-danger');
                            var same = duplication(tel);
                            if (same){
                                $('#phone_error').text('Phone Number cannot have 7 same Numbers');
                                $('#phone_number').addClass('border-danger');
                                $(window).scrollTop(350);
                                return false;
                            }else{
                                $('#phone_error').text('');
                                $('#phone_number').removeClass('border-danger');
                            }
                        }
                    }

                    // var zip_code=$('#zip_code').val();
                    // if(zip_code.length=='')
                    // {
                    //     $('#zipcode_error').text('Please put a valid zipcode');
                    //     $('#zip_code').addClass('border-danger');
                    //     $(window).scrollTop(350);
                    //     return false;
                    // }
                    // else{
                    //     $('#zipcode_error').text('');
                    //     $('#zip_code').removeClass('border-danger');
                    // }


                    // var state=$('#state').val();
                    // if(state.length=='')
                    // {
                    //     $('#state_error').text('Please select state');
                    //     $('#state').addClass('border-danger');
                    //     $(window).scrollTop(350);
                    //     return false;
                    // }
                    // else{
                    //     $('#state_error').text('');
                    //     $('#state').removeClass('border-danger');
                    // }

                    var consultation_fee = $('#consultation_fee').val();

                    if (consultation_fee == '') {
                        $('#consultation_fee_error').text('Please insert your consultation fee');
                        $('#consultation_fee').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    } else {
                        if ($('#follow_up_fee_switch').prop('checked')) {
                            if ($('#follow_up_fee').val() == '') {
                                $('#follow_up_fee_error').text('Please insert your follow up fee');
                                $('#follow_up_fee').addClass('border-danger');
                                $(window).scrollTop(350);
                                return false;
                            }
                        }
                    }


                    var city=$('#city').val();
                    if(city.length=='')
                    {
                        $('#city_error').text('Please select city');
                        $('#city').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else{
                        $('#city_error').text('');
                        $('#city').removeClass('border-danger');
                    }

                    var address=$('#address').val();
                    if(address.length=='')
                    {
                        $('#address_error').text('Please insert your address');
                        $('#address').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else if (address.length>300){
                        $('#address_error').text('Character limit is 300');
                        $('#address').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else{
                        $('#address_error').text('');
                        $('#address').removeClass('border-danger');
                    }

                    var npi=$('#npi').val();
                    exist = nipExists(npi);
                    if(npi.length<5)
                    {
                        $('#npi_error').text('Please put a valid PMDC number');
                        $('#npi').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else if(exist==1)
                    {
                        $('#npi_error').text('NPI number already exist');
                        $('#npi').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else{
                        $('#npi_error').text('');
                        $('#npi').removeClass('border-danger');
                        // var npiRegex =new RegExp("^[a-zA-Z0-9]*$");

                        // if(!npiRegex.test(npi))
                        // {
                        //     $('#npi_error').text('NPI field contain only number');
                        //     $('#npi').addClass('border-danger');
                        //     $(window).scrollTop(350);
                        //     return false;
                        // }else{
                        //     $('#npi_error').text('');
                        //     $('#npi').removeClass('border-danger');
                        // }
                    }

                    var specializations=$('#specializations').val();
                    if(specializations=='')
                    {
                        $('#specializations_error').text('Please select your specialization');
                        $('#specializations').addClass('border-danger');
                        $(window).scrollTop(350);
                        return false;
                    }
                    else{
                        $('#specializations_error').text('');
                        $('#specializations').removeClass('border-danger');
                    }

                    // var licensed_states=$('#licensed_states').val();
                    // if(licensed_states=='')
                    // {
                    //     $('#licensed_state_error').text('Please select your licensed state');
                    //     $('#licensed_states').addClass('border-danger');
                    //     $(window).scrollTop(350);
                    //     return false;
                    // }
                    // else{
                    //     $('#licensed_state_error').text('');
                    //     $('#licensed_states').removeClass('border-danger');
                    // }

                    var email=$('#email').val();
                    // var checker=checkEmail(email);
                    email_flag = emailExists(email);
                    if(email_flag==1)
                    {
                        $('#email_error').text('this email already register');
                        $('#email').addClass('border-danger');
                        $(window).scrollTop(300);
                        return false;
                    }
                    else{
                        $('#email_error').text('');
                        $('#email').removeClass('border-danger');
                    }
                    var emailRegex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

                    if(!emailRegex.test(email))
                    {
                        $('#email_error').text('invalid email');
                        $('#email').addClass('border-danger');
                        $(window).scrollTop(300);
                        return false;
                    }
                    else{
                        $('#email_error').text('');
                        $('#email').removeClass('border-danger');
                    }


                    var username=$('#username').val();

                    username_flag = usernameExists(username);

                    if(username_flag==1)
                    {
                        $('#username_error').text('this username already taken');
                        $('#username').addClass('border-danger');
                        $(window).scrollTop(300);
                        return false;
                    }
                    else{
                        $('#username_error').text('');
                        $('#username').removeClass('border-danger');
                    }

                    var userNameRegex =new RegExp("^[a-zA-Z0-9]+$");
                    if(!userNameRegex.test(username))
                    {
                        $('#username_error').text('invalid username only alphanumeric allowed e.g (john123)');
                        $('#username').addClass('border-danger');
                        $(window).scrollTop(300);
                        return false;
                    }else{
                        $('#username_error').text('');
                        $('#username').removeClass('border-danger');
                    }

                    var password=$('#password').val();

                    if(password.length<8)
                    {
                        $('#password_error').text('Password should be 8 characters long');
                        $('#password').addClass('border-danger');
                        $(window).scrollTop(300);
                        return false;

                    }
                    else{
                        $('#password_error').text('');
                        $('#password').removeClass('border-danger');
                        var specialChar = /[!@#$%^&*()_+\-=]/g;
                        var lowerCaseLetters = /[a-z]/g;
                        var upperCaseLetters = /[A-Z]/g;
                        var numbers = /[0-9]/g;

                        if(!password.match(specialChar))
                        {
                            $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                            $('#password').addClass('border-danger');
                            $(window).scrollTop(300);
                            return false;
                        }else{

                            $('#password_error').text('');
                            $('#password').removeClass('border-danger');
                        }

                        if(!password.match(lowerCaseLetters))
                        {
                            $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                            $('#password').addClass('border-danger');
                            $(window).scrollTop(300);
                            return false;
                        }else{

                            $('#password_error').text('');
                            $('#password').removeClass('border-danger');
                        }

                        if(!password.match(upperCaseLetters))
                        {

                            $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                            $('#password').addClass('border-danger');
                            $(window).scrollTop(300);
                            return false;
                        }else{

                            $('#password_error').text('');
                            $('#password').removeClass('border-danger');
                        }

                        if(!password.match(numbers))
                        {
                            $('#password_error').text('Password should be alphanumeric with a special character and first letter capital e.g (John@123)');
                            $('#password').addClass('border-danger');
                            $(window).scrollTop(300);
                            return false;
                        }
                        else{
                            $('#password_error').text('');
                            $('#password').removeClass('border-danger');
                        }
                    }

                    var password_confirmation=$('#password_confirmation').val();
                    if(password_confirmation!=password)
                    {
                        $('#confirm_password_error').text('Confirm password does not match.');
                        $('#password_confirmation').addClass('border-danger');
                        $(window).scrollTop(300);
                        return false;
                    }
                    else{
                        $('#confirm_password_error').text('');
                        $('#password_confirmation').removeClass('border-danger');
                    }

                    var term = document.getElementById('term');
                    var signature = $("#signature").val();

                    if(signature=="")
                    {
                        $('#sign_error').text('Please Sign and Save it');
                        $('#sig-canvas').addClass('border-danger');
                        return false;
                    }else{
                        $('#sign_error').text('');
                        $('#sig-canvas').removeClass('border-danger');
                    }

                    if(!term.checked)
                    {
                        $('#term_error').text('Please Check Terms and Conditions');
                        $('#term').addClass('border-danger');
                        return false;

                    }else{
                        $('#term_error').text('');
                        $('#term').removeClass('border-danger');
                    }
                    $("#success_modal").modal('show');
                },
                success: (data) =>
                {
                    $("#success_modal").modal('hide');
                    $("#registered_modal").modal('show');
                    location.href="/home";
                },
                complete: function(data)
                {

                }
            });
        }
        else
        {
            alert('Please verify reCAPTCHA');
            return false;
        }
    });

//  ********E-SIGNATURE START************

    // (function() {
    //     window.requestAnimFrame = (function(callback) {
    //       return window.requestAnimationFrame ||
    //         window.webkitRequestAnimationFrame ||
    //         window.mozRequestAnimationFrame ||
    //         window.oRequestAnimationFrame ||
    //         window.msRequestAnimaitonFrame ||
    //         function(callback) {
    //           window.setTimeout(callback, 1000 / 60);
    //         };
    //     })();

    //     var canvas = document.getElementById("sig-canvas");
    //     var ctx = canvas.getContext("2d");
    //     ctx.strokeStyle = "#222222";
    //     ctx.lineWidth = 4;

    //     var drawing = false;
    //     var mousePos = {
    //       x: 0,
    //       y: 0
    //     };
    //     var lastPos = mousePos;

    //     canvas.addEventListener("mousedown", function(e) {
    //       drawing = true;
    //       lastPos = getMousePos(canvas, e);
    //     }, false);

    //     canvas.addEventListener("mouseup", function(e) {
    //       drawing = false;
    //     }, false);

    //     canvas.addEventListener("mousemove", function(e) {
    //       mousePos = getMousePos(canvas, e);
    //     }, false);

    //     // Add touch event support for mobile
    //     canvas.addEventListener("touchstart", function(e) {

    //     }, false);

    //     canvas.addEventListener("touchmove", function(e) {
    //       var touch = e.touches[0];
    //       var me = new MouseEvent("mousemove", {
    //         clientX: touch.clientX,
    //         clientY: touch.clientY
    //       });
    //       canvas.dispatchEvent(me);
    //     }, false);

    //     canvas.addEventListener("touchstart", function(e) {
    //       mousePos = getTouchPos(canvas, e);
    //       var touch = e.touches[0];
    //       var me = new MouseEvent("mousedown", {
    //         clientX: touch.clientX,
    //         clientY: touch.clientY
    //       });
    //       canvas.dispatchEvent(me);
    //     }, false);

    //     canvas.addEventListener("touchend", function(e) {
    //       var me = new MouseEvent("mouseup", {});
    //       canvas.dispatchEvent(me);
    //     }, false);

    //     function getMousePos(canvasDom, mouseEvent) {
    //       var rect = canvasDom.getBoundingClientRect();
    //       return {
    //         x: mouseEvent.clientX - rect.left,
    //         y: mouseEvent.clientY - rect.top
    //       }
    //     }

    //     function getTouchPos(canvasDom, touchEvent) {
    //       var rect = canvasDom.getBoundingClientRect();
    //       return {
    //         x: touchEvent.touches[0].clientX - rect.left,
    //         y: touchEvent.touches[0].clientY - rect.top
    //       }
    //     }

    //     function renderCanvas() {
    //       if (drawing) {
    //         ctx.moveTo(lastPos.x, lastPos.y);
    //         ctx.lineTo(mousePos.x, mousePos.y);
    //         ctx.stroke();
    //         lastPos = mousePos;
    //       }
    //     }

    //     // Prevent scrolling when touching the canvas
    //     document.body.addEventListener("touchstart", function(e) {
    //       if (e.target == canvas) {
    //         e.preventDefault();
    //       }
    //     }, false);
    //     document.body.addEventListener("touchend", function(e) {
    //       if (e.target == canvas) {
    //         e.preventDefault();
    //       }
    //     }, false);
    //     document.body.addEventListener("touchmove", function(e) {
    //       if (e.target == canvas) {
    //         e.preventDefault();
    //       }
    //     }, false);

    //     (function drawLoop() {
    //       requestAnimFrame(drawLoop);
    //       renderCanvas();
    //     })();

    //     function clearCanvas() {
    //       canvas.width = canvas.width;
    //     }

    //     // Set up the UI
    //     var sigText = document.getElementById("sig-dataUrl");
    //     var sigImage = document.getElementById("sig-image");
    //     var clearBtn = document.getElementById("sig-clearBtn");
    //     var submitBtn = document.getElementById("sig-submitBtn");
    //     clearBtn.addEventListener("click", function(e) {
    //       e.preventDefault()
    //       clearCanvas();
    //       sigText.innerHTML = "Data URL for your signature will go here!";
    //       sigImage.setAttribute("src", "");
    //     }, false);
    //     submitBtn.addEventListener("click", function(e) {
    //       var dataUrl = canvas.toDataURL();
    //       sigText.innerHTML = dataUrl;
    //       sigImage.setAttribute("src", dataUrl);
    //     }, false);

    //   })();



//  ********E-SIGNATURE ENDS************



//  ********SIGNUP FORM ENDS************

