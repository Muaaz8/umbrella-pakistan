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


$(document).click(function (event) {

    /// If navbar-collapse is not among targets of event
    if (!$(event.target).is('.navbar-collapse *')) {

      /// Collapse every navbar-collapse
      $('.navbar-collapse').collapse('hide');

    }
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
  if (x.type === "password") {
    x.type = "text";
    // y.type = "text";
  } else {
    x.type = "password";
    // y.type = "password";
  }
  if (y.type === "password") {
    y.type = "text";
  } else {
    y.type = "password";
  }

}

$(document).ready(function(){
    $("#rep_div").hide();
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
        $(window).scrollTop(330);


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
        $(window).scrollTop(330);

        var radio1=$('#radio1').prop("checked");
        var radio2=$('#radio2').prop("checked");
        if(radio1==false && radio2==false)
        {
            $('#typeError').text('Please choose at least one option');
            return false;
        }
        else{
            $('#typeError').text('');
        }

        if(radio1==true)
        {
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
                // var changeFormat =  moment(date_of_birth).format('MM-DD-YYYY');
                var [month,day,year] = date_of_birth.split("-")
                var date_of_birth = new Date(year,month-1,day);
                var today = new Date();
                var age = Math.floor((today-date_of_birth) / (365.25 * 24 * 60 * 60 * 1000));
                if(year[0] == 0 && year[1] == 0){
                    $('#rep_bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                    $('#rep_date_of_birth').addClass('border-danger');
                    return false;
                }
                if(month > 12 || day > 31){
                    $('#bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                    $('#date_of_birth').addClass('border-danger');
                    return false;
                }
                if(date_of_birth > today){
                    $('#bod_error').text('Future date cannot be selected as DOB');
                    $('#date_of_birth').addClass('border-danger');
                    return false;
                }
                else if(age<18)
                {
                    $('#bod_error').text('You must be 18 years old');
                    $('#date_of_birth').addClass('border-danger');
                    $(document).ready(function(){
                        $("#myModal").modal("show");})
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
                // var phoneRegex =new RegExp('^(0|1)[0-9]{1,45}$');

                if(!phoneRegex.test(tel))
                {
                    $('#phone_error').text('Phone field contain only number and cannot start with 0 or 1');
                    $('#phone_number').addClass('border-danger');
                    return false;
                }else{
                    $('#phone_error').text('');
                    $('#phone_number').removeClass('border-danger');
                }
            }

            var zip_code=$('#zip_code').val();
            console.log(zip_code);
            if(zip_code.length=='')
            {
                $(window).scrollTop(100);
                $('#zipcode_error').text('Please put a valid zipcode');
                $('#zip_code').addClass('border-danger');
                return false;
            }
            else{
                $('#zipcode_error').text('');
                $('#zip_code').removeClass('border-danger');
            }


            var state=$('.state').val();
            if(state.length=='')
            {
                $(window).scrollTop(790);
                $('.state_error').text('Please select state');
                $('.state').addClass('border-danger');
                return false;
            }
            else{
                $('.state_error').text('');
                $('.state').removeClass('border-danger');
            }

            var city=$('#city').val();
            if(city.length=='')
            {
                $(window).scrollTop(790);
                $('.city_error').text('Please select city');
                $('.city').addClass('border-danger');
                return false;
            }
        else{
                $('.city_error').text('');
                $('.city').removeClass('border-danger');
            }


            // var appartment=$('#appartment').val();
            // if(appartment.length=='')
            // {
            //     $(window).scrollTop(790);
            //     $('#appartment_error').text('Please insert your street/appartment address');
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
        }
        else
        {
            var nameRegex = /^[a-zA-Z]+ ?$/;
            var rep_name=$('#rep_name').val();
            if(!nameRegex.test(rep_name))
            {
                $('#rep_name_error').text('please enter your first name e.g (john)');
                $('#rep_name').addClass('border-danger');
                return false;
            }else{
                $('#rep_name_error').text('');
                $('#rep_name').removeClass('border-danger');
            }

            var rep_fullname = /^[a-zA-Z_ ]+$/g;
            var fullname=$('#rep_fullname').val();
            if(!rep_fullname.test(fullname))
            {
                $('#rep_fullname_error').text('please enter your full name e.g (john smith)');
                $('#rep_fullname').addClass('border-danger');
                return false;
            }else{
                $('#rep_fullname_error').text('');
                $('#rep_fullname').removeClass('border-danger');
            }

            var lnameRegex = /^[a-zA-Z]+ ?$/;
            var last_name=$('#rep_last_name').val();
            if(!lnameRegex.test(last_name))
            {
                $('#rep_lname_error').text('please enter your last name e.g (kent)');
                $('#rep_last_name').addClass('border-danger');
                return false;
            }else{
                $('#rep_lname_error').text('');
                $('#rep_last_name').removeClass('border-danger');
            }

            var gender=$('#rep_gender').val();
            if(gender=="")
            {
                $('#rep_gender_error').text('Please choose gender');
                $('#rep_gender').addClass('border-danger');
                return false;
            }else{
                $('#rep_gender_error').text('');
                $('#rep_gender').removeClass('border-danger');
            }

            var relation=$('#rep_relation').val();
            if(relation=="")
            {
                $('#rep_relation_error').text('Please choose representative relation');
                $('#rep_relation').addClass('border-danger');
                return false;
            }else{
                $('#rep_relation_error').text('');
                $('#rep_relation').removeClass('border-danger');
            }

            var date_of_birth=$('#rep_date_of_birth').val();
            if(date_of_birth=='')
            {
                $('#rep_bod_error').text('Please enter your DOB e.g(mm-dd-yyyy)');
                $('#rep_date_of_birth').addClass('border-danger');
                return false;
            }else{
                $('#bod_error').text('');
                $('#rep_date_of_birth').removeClass('border-danger');
                // var changeFormat =  moment(date_of_birth).format('MM-DD-YYYY');
                var [month,day,year] = date_of_birth.split("-")
                var date_of_birth = new Date(year,month-1,day);
                var today = new Date();
                var age = Math.floor((today-date_of_birth) / (365.25 * 24 * 60 * 60 * 1000));
                console.log(year[1]);
                if(year[0] == 0 && year[1] == 0){
                    $('#rep_bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                    $('#rep_date_of_birth').addClass('border-danger');
                    return false;
                }
                if(month > 12 || day > 31){
                    $('#rep_bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                    $('#rep_date_of_birth').addClass('border-danger');
                    return false;
                }
                if(date_of_birth > today){
                    $('#rep_bod_error').text('Future date cannot be selected as DOB');
                    $('#rep_date_of_birth').addClass('border-danger');
                    return false;
                }else if(age>100){
                    $('#rep_bod_error').text('You must be less than 100 years old');
                    $('#rep_date_of_birth').addClass('border-danger');
                    return false;
                }else{
                    $('#rep_bod_error').text('');
                    $('#rep_date_of_birth').removeClass('border-danger');
                }
            }


            var tel=$('#rep_phone_number').val();

            if(tel.length>10 || tel.length<9)
            {
                $('#rep_phone_error').text('Please put a valid number');
                $('#rep_phone_number').addClass('border-danger');
                return false;
            }
            else{
                $('#rep_phone_error').text('');
                $('#rep_phone_number').removeClass('border-danger');
                var phoneRegex =new RegExp('^(?!0|1)[0-9]{1,45}$');
                // var phoneRegex =new RegExp('^(0|1)[0-9]{1,45}$');

                if(!phoneRegex.test(tel))
                {
                    $('#rep_phone_error').text('Phone field contain only number and cannot start with 0 or 1');
                    $('#rep_phone_number').addClass('border-danger');
                    return false;
                }else{
                    $('#rep_phone_error').text('');
                    $('#rep_phone_number').removeClass('border-danger');
                }
            }

            var zip_code=$('#rep_zip_code').val();
            if(zip_code.length=='')
            {
                $('#rep_zipcode_error').text('Please put a valid zipcode');
                $('#rep_zip_code').addClass('border-danger');
                return false;
            }
            else{
                $('#rep_zipcode_error').text('');
                $('#rep_zip_code').removeClass('border-danger');
            }


            var state=$('#rep_state').val();
            if(state.length=='')
            {
                $('#rep_state_error').text('Please select state');
                $('#rep_state').addClass('border-danger');
                return false;
            }
            else{
                $('#rep_state_error').text('');
                $('#rep_state').removeClass('border-danger');
            }

            var city=$('#rep_city').val();
            if(city.length=='')
            {
                $('#rep_city_error').text('Please select city');
                $('#rep_city').addClass('border-danger');
                return false;
            }
            else{
                $('#rep_city_error').text('');
                $('#rep_city').removeClass('border-danger');
            }


            // var appartment=$('#rep_appartment').val();
            // if(appartment.length=='')
            // {
            //     $('#rep_appartment_error').text('Please insert your street/appartment address');
            //     $('#rep_appartment').addClass('border-danger');
            //     return false;
            // }
            // else{
            //     $('#rep_appartment_error').text('');
            //     $('#rep_appartment').removeClass('border-danger');
            // }

            var address=$('#rep_address').val();
            if(address.length=='')
            {
                $('#rep_address_error').text('Please insert your address');
                $('#rep_address').addClass('border-danger');
                return false;
            }
            else if (address.length>300){
                $('#rep_address_error').text('Character limit is 300');
                $('#rep_address').addClass('border-danger');
                return false;
            }
            else{
                $('#rep_address').text('');
                $('#rep_address').removeClass('border-danger');
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

        }
    });


    $(".thirdNext").click(function(){
        $(window).scrollTop(330);

        var ext = $('#upload_record').val().split('.').pop().toLowerCase();
        if(ext!='')
        {
            if($.inArray(ext, ['pdf','png','jpg','jpeg']) == -1)
            {
                $('#file_error').text('Please choose valid file e.g (pdf,png,jpg,jpeg)');
                $('#upload_record').addClass('border-danger');
                return false;
            }
            else
            {
                $('#file_error').text('');
                $('#upload_record').removeClass('border-danger');

            }
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




    $("#term").change(function() {
        if(this.checked) {
            $('#term').val('1');
            $('.forthNext').prop("disabled", false);
            $('.forthNext').css("background", '#08295a');
        }else{
            $('.forthNext').css("background", '#a9b9d0');
            $('.forthNext').prop("disabled", true);
            $('#term').val('0');
        }
    });


    $("#zip_code").keyup(function(){
        var zip = $("#zip_code").val();
        var length = $("#zip_code").val().length;
        if(length >= 5) {
            $('.state').html('<option value="">Select State</option>');
            $('.city').html('<option value="">Select City</option>');
            $.ajax({
                type: "POST",
                url: "/get_states_cities",
                data: {
                    zip: zip,
                },
                success: function(data) {
                    console.log(data);
                    if (data == "") {
                        $('#zipcode_error').text('Please enter a valid zipcode');
                        $('#zip_code').addClass('border-danger');
                        return false;
                    } else {
                        $('#zipcode_error').text('');
                        $('#zip_code').removeClass('border-danger');
                        $('.state').html('<option value="'+data.state_id+'">'+data.state+'</option>');
                        $('.city').html('<option value="'+data.city_id+'">'+data.city+'</option>');
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
            //         alert(data);
            //         $('.state').html('<option value="">Select State</option>');
            //         $('.city').html('<option value="">Select City</option>');
            //         if (data.country_id == ""){
            //             $('.zipcode_error').text('Please enter a valid zipcode');
            //             $('.zip_code').addClass('border-danger');
            //             return false;
            //         }
            //         else {
            //             $('.zipcode_error').text('');
            //             $('#country').val(data.country_id);
            //             $('.zip_code').removeClass('border-danger');
            //             $('.state').html('<option value="'+data.state_id+'">'+data.state_name+'</option>');
            //             $.each(data.all_citys, function(key, value) {
            //                 $('.city').append('<option value="'+value.id+'">'+value.name+'</option>');
            //             });

            //         }
            //     },
            // });
        }
    });
    $("#rep_zip_code").keyup(function(){
        var zip = $("#rep_zip_code").val();
        var length = $("#rep_zip_code").val().length;
        if(length >= 5) {
            $('#rep_state').html('<option value="">Select State</option>');
            $('#rep_city').html('<option value="">Select City</option>');
            $.ajax({
                type: "POST",
                url: "/get_states_cities",
                data: {
                    zip: zip,
                },
                success: function(data) {
                    if (data == "") {
                        $('#rep_zipcode_error').text('Please enter a valid zipcode');
                        $('#rep_zip_code').addClass('border-danger');
                        return false;
                    } else {
                        $('#rep_zipcode_error').text('');
                        $('#rep_zip_code').removeClass('border-danger');
                        $('#rep_state').html('<option value="'+data.state_id+'">'+data.state+'</option>');
                        $('#rep_city').html('<option value="'+data.city_id+'">'+data.city+'</option>');
                    }
                    // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                },
                // success: function(data) {
                //     $('#rep_state').html('<option value="">Select State</option>');
                //     $('#rep_city').html('<option value="">Select City</option>');
                //     if (data.country_id == ""){
                //         $('#rep_zipcode_error').text('Please enter a valid zipcode');
                //         $('#rep_zip_code').addClass('border-danger');
                //         return false;
                //     }
                //     else {
                //         $('.zipcode_error').text('');
                //         $('#rep_country').val(data.country_id);
                //         $('#rep_zip_code').removeClass('border-danger');
                //         $('#rep_state').html('<option value="'+data.state_id+'">'+data.state_name+'</option>');
                //         $.each(data.all_citys, function(key, value) {
                //             $('#rep_city').append('<option value="'+value.id+'">'+value.name+'</option>');
                //         });
                //     }
                // },
            });
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

    $("#terms_and_cond").change(function() {
        if(this.checked) {
            $('#term').val('1');
            $('.reg__submit_btn').prop("disabled", false);
            $('.forthNext').css("background", '#08295a');
        }else{
            $('.forthNext').css("background", '#a9b9d0');
            $('.reg__submit_btn').prop("disabled", true);
            $('#term').val('0');
        }
    });


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

    $('#radio2').click(function(){
        // var demovalue = $(this).val();
        // $('#typeError').text('');
        // $("div.myDiv").hide();
        // $("#"+demovalue).show();
        $("#rep_div").show();
    });
    $('#radio1').click(function(){
        // var demovalue = $(this).val();
        // $('#typeError').text('');
        // $("div.myDiv").hide();
        // $("#"+demovalue).show();
        $("#rep_div").hide();
    });

    $('#pat_reg_form').submit(function(e)
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
                    var radio1=$('#radio1').prop("checked");
                    var radio2=$('#radio2').prop("checked");
                    if(radio1==false && radio2==false)
                    {
                        $('#typeError').text('Please choose at least one option');
                        return false;
                    }
                    else{
                        $('#typeError').text('');
                    }

                    if(radio2==true)
                    {
                        var rep_fullname = /^[a-zA-Z_ ]+$/g;
                        var fullname=$('#rep_fullname').val();
                        if(!rep_fullname.test(fullname))
                        {
                            $('#rep_fullname_error').text('please enter your full name e.g (john smith)');
                            $('#rep_fullname').addClass('border-danger');
                            return false;
                        }else{
                            $('#rep_fullname_error').text('');
                            $('#rep_fullname').removeClass('border-danger');
                        }
                        var relation=$('#rep_relation').val();
                        if(relation=="")
                        {
                            $('#rep_relation_error').text('Please choose representative relation');
                            $('#rep_relation').addClass('border-danger');
                            return false;
                        }else{
                            $('#rep_relation_error').text('');
                            $('#rep_relation').removeClass('border-danger');
                        }
                    }

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
                        // var changeFormat =  moment(date_of_birth).format('MM-DD-YYYY');
                        var [month,day,year] = date_of_birth.split("-")
                        var date_of_birth = new Date(year,month-1,day);
                        var today = new Date();
                        var age = Math.floor((today-date_of_birth) / (365.25 * 24 * 60 * 60 * 1000));
                        if(year[0] == 0 && year[1] == 0){
                            $('#rep_bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                            $('#rep_date_of_birth').addClass('border-danger');
                            return false;
                        }
                        if(month > 12 || day > 31){
                            $('#bod_error').text('Invalid Date! Please enter your DOB e.g(mm-dd-yyyy)');
                            $('#date_of_birth').addClass('border-danger');
                            return false;
                        }
                        if(date_of_birth > today){
                            $('#bod_error').text('Future date cannot be selected as DOB');
                            $('#date_of_birth').addClass('border-danger');
                            return false;
                        }
                        else if(age<18)
                        {
                            $('#bod_error').text('You must be 18 years old');
                            $('#date_of_birth').addClass('border-danger');
                            $(document).ready(function(){
                                $("#myModal").modal("show");})
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
                        // var phoneRegex =new RegExp('^(0|1)[0-9]{1,45}$');

                        if(!phoneRegex.test(tel))
                        {
                            $('#phone_error').text('Phone field contain only number and cannot start with 0 or 1');
                            $('#phone_number').addClass('border-danger');
                            return false;
                        }else{
                            $('#phone_error').text('');
                            $('#phone_number').removeClass('border-danger');
                        }
                    }

                    var city=$('#city').val();
                    if(city.length=='')
                    {
                        $(window).scrollTop(790);
                        $('.city_error').text('Please select city');
                        $('.city').addClass('border-danger');
                        return false;
                    }
                    else{
                        $('.city_error').text('');
                        $('.city').removeClass('border-danger');
                    }

                    // var address=$('#address').val();
                    // if(address.length=='')
                    // {
                    //     $('#address_error').text('Please insert your address');
                    //     $('#address').addClass('border-danger');
                    //     return false;
                    // }
                    // else if (address.length>300){
                    //     $('#address_error').text('Character limit is 300');
                    //     $('#address').addClass('border-danger');
                    //     return false;
                    // }
                    // else{
                    //     $('#address_error').text('');
                    //     $('#address').removeClass('border-danger');
                    // }

                    // var email=$('#email').val();
                    // // var checker=checkEmail(email);
                    // email_flag = emailExists(email);
                    // if(email_flag==1)
                    // {
                    //     $('#email_error').text('this email already register');
                    //     $('#email').addClass('border-danger');
                    //     return false;
                    // }
                    // else{
                    //     $('#email_error').text('');
                    //     $('#email').removeClass('border-danger');
                    // }
                    // var emailRegex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                    // if(!emailRegex.test(email))
                    // {
                    //     $('#email_error').text('invalid email');
                    //     $('#email').addClass('border-danger');
                    //     return false;
                    // }
                    // else{
                    //     $('#email_error').text('');
                    //     $('#email').removeClass('border-danger');
                    // }
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

                    var terms = document.getElementById("terms_and_cond");
                    if(terms.checked)
                    {
                        $('#term').val('1');
                        $('#terms_and_cond_error').text('');
                    }
                    else
                    {
                        $('#term').val('0');
                        $('#terms_and_cond_error').text('Check Terms and Conditions');
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

