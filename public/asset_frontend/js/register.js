$(document).ready(function() {
    // $(document).click(function(){
    //    rel = $('#relation').val();
    //    if(rel == "other"){
    //        $("#other-rel").show();
    //    }
    //    else{
    //     $("#other-rel").hide();

    //    }
    //     console.log(rel);

    // });
    $("#pat-rep").hide();

    $("#state").change(function() {
        id = $(this).val();
        console.log(id);
        $.ajax({
            type: "POST",
            url: "/get_cities",
            data: {
                id: id,
            },
            success: function(data) {
                $("#city").text("");
                $("#city").append(
                    '<option value="" disabled selected>Select City</option>'
                );
                $.each(data, function(key, value) {
                    $("#city").append(
                        '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                    );
                });
            },
        });
    });
    $('input:radio[name="rep_radio"]')
        .on("change", function() {
            console.log("in");
            if ($(this).is(":checked") && $(this).val() == "patient") {
                $("#rep-div").hide();
            } else if (
                $(this).is(":checked") &&
                $(this).val() == "representative"
            ) {
                console.log("rep");
                $("#rep-div").show();
            }
        })
        .change();
    var current_fs, next_fs, previous_fs;
    var left, opacity, scale;
    var animating;
    $("#rep-div").hide();
    $(".next").click(function() {
        id = $(this).attr("id");
        console.log(id);
        if (id == "step_1_next") {
            email = $("#email").val();
            password = $("#password").val();
            confirm = $("#password-confirm").val();
            username = $("#username").val();
            validated = true;
            email_flag = emailExists(email);
            if (email != "" && (!validateEmail(email) || email_flag == "1")) {
                validated = false;
                $("#email").css("border", "2px red solid");
                email_flag == "1" ?
                    $("#email_exist_err").addClass("d-block") :
                    $("#email_err").addClass("d-block");
            } else {
                $("#email").css("border", "2px solid #ced4da");
                $("#email_exist_err").removeClass("d-block");
                $("#email_err").removeClass("d-block");
            }
            if (password != "" && password != confirm) {
                validated = false;
                $("#password").css("border", "2px red solid");
                $("#password-confirm").css("border", "2px red solid");
                $("#password").val("");
                $("#password-confirm").val("");
                $("#password_err").addClass("d-block");
                $("#password_validate_err").removeClass("d-block");
            } else {
                $("#password_err").removeClass("d-block");
                if (password != "" && !validatePassword(password)) {
                    validated = false;
                    $("#password").css("border", "2px red solid");
                    $("#password-confirm").css("border", "2px red solid");
                    $("#password").val("");
                    $("#password-confirm").val("");
                    $("#password_validate_err").addClass("d-block");
                } else $("#password_validate_err").removeClass("d-block");
            }
            if (username != "") {
                username_flag = usernameExists(username);
                if (!validateUsername(username) || username_flag == "1") {
                    validated = false;
                    $("#username").css("border", "2px red solid");
                    username_flag == "1" ?
                        $("#username_exist_err").addClass("d-block") :
                        $("#username_err").addClass("d-block");
                } else {
                    $("#username").css("border", "2px solid #ced4da");
                    $("#username_err").removeClass("d-block");
                    $("#username_exist_err").removeClass("d-block");
                }
            }
            if (validated) {
                $("#email").css("border", "1px solid #ccc");
                $("#password").css("border", "1px solid #ccc");
                $("#password-confirm").css("border", "1px solid #ccc");
                $("#username").css("border", "1px solid #ccc");
                if (
                    email != "" &&
                    password != "" &&
                    confirm != "" &&
                    username != ""
                ) {
                    console.log("step 1 clear");
                    $("#step_1_err").removeClass("d-block");
                    if (animating) return false;
                    animating = true;

                    current_fs = $(this).parent();
                    next_fs = $(this).parent().next();
                    console.log(next_fs);
                    $("#progressbar li")
                        .eq($("fieldset").index(next_fs))
                        .addClass("active");
                    next_fs.show();
                    // current_fs.hide();
                    current_fs.animate({ opacity: 0 }, {
                        step: function(now, mx) {
                            console.log(now);
                            console.log(mx);
                            scale = 1 - (1 - now) * 0.2;
                            left = now * 50 + "%";
                            top = "200px";
                            opacity = 1 - now;
                            current_fs.css({
                                transform: "scale(" + scale + ")",
                            });
                            next_fs.css({
                                left: left,
                                "margin-top": top,
                                opacity: opacity,
                            });
                        },
                        duration: 1000,
                        complete: function() {
                            current_fs.hide();
                            animating = false;
                        },
                        easing: "easeInOutBack",
                    });
                } else {
                    $("#step_1_err").addClass("d-block");
                }
            }
        } else if (id == "step_2_next") {
            name = $("#name").val();
            last_name = $("#last_name").val();
            gender = $("#gender").find(":selected").val();
            datetimepicker = $("#datetimepicker").val();
            phone_number = $("#phone_number").val();
            state = $("#state").find(":selected").val();
            city = $("#city").find(":selected").val();
            street = $("#street").val();
            zip_code = $("#zip_code").val();
            validated = true;
            if (name != "" && hasCharactersOnly(name)) {
                validated = false;
                $("#name").css("border", "2px red solid");
                $("#name_err").addClass("d-block");
            } else {
                $("#name_err").removeClass("d-block");
            }
            if (last_name != "" && hasCharactersOnly(last_name)) {
                validated = false;
                $("#last_name").css("border", "2px red solid");
                $("#last_name_err").addClass("d-block");
            } else {
                $("#last_name_err").removeClass("d-block");
            }
            if (datetimepicker != "" && !dobValidate(datetimepicker)) {
                validated = false;
                $("#datetimepicker").css("border", "2px red solid");
                $("#dob_err").addClass("d-block");
            } else {
                $("#dob_err").removeClass("d-block");
            }
            if (
                (phone_number != "" && phone_number.length > 15) ||
                phone_number < 0
            ) {
                validated = false;
                $("#phone_number").css("border", "2px red solid");
                $("#phone_err").addClass("d-block");
            } else {
                $("#phone_err").removeClass("d-block");
            }
            if (street != "" && street.length > 50) {
                validated = false;
                $("#street").css("border", "2px red solid");
                $("#address_err").addClass("d-block");
            } else {
                $("#address_err").removeClass("d-block");
            }
            if ((zip_code != "" && zip_code.length > 10) || zip_code < 0) {
                validated = false;
                $("#zip_code").css("border", "2px red solid");
                $("#zip_err").addClass("d-block");
            } else {
                $("#zip_err").removeClass("d-block");
            }
            if ($("#radio2").is(":checked")) {
                if (zip_code != "" && zip_code.length > 10) {
                    validated = false;
                    $("#zip_code").css("border", "2px red solid");
                    $("#zip_err").addClass("d-block");
                } else {
                    $("#zip_err").removeClass("d-block");
                }
            }
            if (validated) {
                $("#name").css("border", "1px solid #ccc");
                $("#last_name").css("border", "1px solid #ccc");
                $("#datetimepicker").css("border", "1px solid #ccc");
                $("#phone_number").css("border", "1px solid #ccc");
                $("#zip_code").css("border", "1px solid #ccc");
                $("#password-confirm").css("border", "1px solid #ccc");
                $("#username").css("border", "1px solid #ccc");
                if (
                    name != "" &&
                    last_name != "" &&
                    gender != "" &&
                    datetimepicker != "" &&
                    phone_number != "" &&
                    state != "" &&
                    city != "" &&
                    street != "" &&
                    zip_code != ""
                ) {
                    if ($("#radio2").is(":checked")) {
                        if (
                            $("#full_name").val() != "" &&
                            $("#relation").val() != ""
                        ) {
                            $("#step_2_err").removeClass("d-block");
                            if (animating) return false;
                            animating = true;

                            current_fs = $(this).parent();
                            next_fs = $(this).parent().next();
                            $("#progressbar li")
                                .eq($("fieldset").index(next_fs))
                                .addClass("active");
                            next_fs.show();
                            current_fs.animate({ opacity: 0 }, {
                                step: function(now, mx) {
                                    // console.log(now);
                                    scale = 1 - (1 - now) * 0.2;
                                    left = now * 50 + "%";
                                    top = "200px";
                                    opacity = 1 - now;
                                    current_fs.css({
                                        transform: "scale(" + scale + ")",
                                    });
                                    next_fs.css({
                                        left: left,
                                        "margin-top": top,
                                        opacity: opacity,
                                    });
                                },
                                duration: 1000,
                                complete: function() {
                                    current_fs.hide();
                                    animating = false;
                                },
                                easing: "easeInOutBack",
                            });
                        } else {
                            $("#step_2_err").addClass("d-block");
                        }
                    } else {
                        $("#step_2_err").removeClass("d-block");
                        if (animating) return false;
                        animating = true;

                        current_fs = $(this).parent();
                        next_fs = $(this).parent().next();
                        $("#progressbar li")
                            .eq($("fieldset").index(next_fs))
                            .addClass("active");
                        next_fs.show();
                        current_fs.animate({ opacity: 0 }, {
                            step: function(now, mx) {
                                // console.log(now);
                                scale = 1 - (1 - now) * 0.2;
                                left = now * 50 + "%";
                                top = "200px";
                                opacity = 1 - now;
                                current_fs.css({
                                    transform: "scale(" + scale + ")",
                                });
                                next_fs.css({
                                    left: left,
                                    "margin-top": top,
                                    opacity: opacity,
                                });
                            },
                            duration: 1000,
                            complete: function() {
                                current_fs.hide();
                                animating = false;
                            },
                            easing: "easeInOutBack",
                        });
                    }
                } else {
                    $("#step_2_err").addClass("d-block");
                }
            }
        } else if (id == "step_3_next") {
            npi = $("#nip_number").val();
            // upin = $("#upin").val();
            specialization = $("#specialization").val();
            liscencestate = $("#licensed_states").val();
            id_card_front = document.getElementById("id_card_front");
            id_card_back = document.getElementById("id_card_back");
            if (
                npi != "" &&
                // upin != "" &&
                specialization != "" &&
                licensed_states != "" &&
                id_card_front.files[0] != undefined &&
                id_card_back.files[0] != undefined
            ) {
                $("#step_3_err").removeClass("d-block");
                if (animating) return false;
                animating = true;

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                $("#progressbar li")
                    .eq($("fieldset").index(next_fs))
                    .addClass("active");
                next_fs.show();
                // current_fs.hide();
                current_fs.animate({ opacity: 0 }, {
                    step: function(now, mx) {
                        // console.log(now);
                        scale = 1 - (1 - now) * 0.2;
                        left = now * 50 + "%";
                        top = "200px";
                        opacity = 1 - now;
                        current_fs.css({
                            transform: "scale(" + scale + ")",
                        });
                        next_fs.css({
                            left: left,
                            "margin-top": top,
                            opacity: opacity,
                        });
                    },
                    duration: 1000,
                    complete: function() {
                        current_fs.hide();
                        animating = false;
                    },
                    easing: "easeInOutBack",
                });
            } else {
                $("#step_3_err").addClass("d-block");
            }
        } else if (id == "step_4_next") {
            $("#step_4_err").removeClass("d-block");
            if (animating) return false;
            animating = true;

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();
            $("#progressbar li")
                .eq($("fieldset").index(next_fs))
                .addClass("active");
            next_fs.show();
            // current_fs.hide();
            current_fs.animate({ opacity: 0 }, {
                step: function(now, mx) {
                    // console.log(now);
                    scale = 1 - (1 - now) * 0.2;
                    left = now * 50 + "%";
                    top = "200px";
                    opacity = 1 - now;
                    current_fs.css({
                        transform: "scale(" + scale + ")",
                    });
                    next_fs.css({
                        left: left,
                        "margin-top": top,
                        opacity: opacity,
                    });
                },
                duration: 1000,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                },
                easing: "easeInOutBack",
            });
        }
    });

    $(".previous").click(function() {
        if (animating) return false;
        animating = true;
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        $("#progressbar li")
            .eq($("fieldset").index(current_fs))
            .removeClass("active");
        previous_fs.show();
        current_fs.animate({ opacity: 0 }, {
            step: function(now, mx) {
                scale = 0.8 + (1 - now) * 0.2;
                left = (1 - now) * 50 + "%";
                opacity = 1 - now;
                current_fs.css({ left: left });
                previous_fs.css({
                    transform: "scale(" + scale + ")",
                    opacity: opacity,
                });
            },
            duration: 1000,
            complete: function() {
                current_fs.hide();
                animating = false;
            },
            easing: "easeInOutBack",
        });
    });

    $("#datetimepicker").datetimepicker({
        lang: "en",
        timepicker: false,
        closeOnDateSelect: true,
        format: "m/d/Y",
        maxDate: 0,
        yearStart: 1900,
    });

    $("#zip_code").keyup(function() {
        var zip = $("#zip_code").val();
        var length = $("#zip_code").val().length;
        if (length >= 5) {
            $.ajax({
                type: "POST",
                url: "/getCityStateByZipCode",
                data: {
                    zip: zip,
                },
                success: function(data) {
                    console.log(data);
                    if (data.country_id == "") {
                        validated = false;
                        $("#zip_code").css("border", "2px red solid");
                        $("#valid_zip").addClass("d-block");
                        $("#state")[0].selectedIndex = 0;
                        $("#city")[0].selectedIndex = 0;
                    } else {
                        $("#zip_code").css("border", "1px solid #ccc");
                        $("#valid_zip").removeClass("d-block");
                        $("#country_id").val(data.country_id);
                        $("#state")
                            .val(data.state_id)
                            .attr("selected", "selected");
                        stateChange(data.state_id, data.city_id);
                    }
                    // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                },
            });
        }
    });

    $(".statesSelect").select2({
        placeholder: "Select States",
        ajax: {
            url: "/getstatesSelect",
            type: "GET",
            delay: 250,
            quietMillis: 100,
            dataType: "json",
            data: function(params) {
                return {
                    term: params.term,
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(obj) {
                        return {
                            id: obj.id,
                            text: obj.text,
                        };
                    }),
                };
            },
            cache: true,
        },
    });

    function stateChange(id, city_id) {
        $.ajax({
            type: "POST",
            url: "/get_cities",
            data: {
                id: id,
            },
            success: function(data) {
                $.each(data, function(key, value) {
                    if (value.id == city_id) {
                        $("#city").append(
                            '<option value="' +
                            value.id +
                            '" selected>' +
                            value.name +
                            "</option>"
                        );
                    } else {
                        $("#city").append(
                            '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>"
                        );
                    }
                });
            },
        });
    }
});

function hasNumber(myString) {
    return /\d/.test(myString);
}

$("#submit").click(function(e) {
    var canvas = document.getElementById("sig-canvas");
    const blank = document.createElement("canvas");
    blank.width = canvas.width;
    blank.height = canvas.height;
    res = canvas.toDataURL() === blank.toDataURL();
    // console.log(res);
    if (res != true) {
        var dataURL = canvas.toDataURL();
        $("#sig-input").val(dataURL);
        $("#step_4_err").removeClass("d-block");
        document.getElementById("sig-canvas").value = dataURL;
        var sign = document.getElementById("sig-canvas");
        sign.value = dataURL;
        $("#msform").submit();
    } else {
        $("#step_4_err").addClass("d-block");
        e.preventDefault();
    }
});

$('#terms').click(function() {
    // console.log('checked::'+this.checked)
    this.value = this.checked;
    if(this.checked==1){
        $('#submit').removeClass('register_btn')
        $('#submit').addClass('button_ui')
    }else{
        $('#submit').removeClass('button_ui')
        $('#submit').addClass('register_btn')
    }

});
function hasCharactersOnly(myString) {
    return /[`!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~0-9]/.test(myString);
}

function validateEmail(email) {
    const re =
        /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
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

function validateUsername(username) {
    const re = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
    return re.test(String(username).toLowerCase());
}

function validatePassword(password) {
    const re =
        /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&./\()+_-])[A-Za-z\d@$!%*#?&./\()+_-]{8,32}$/;
    return re.test(String(password).toLowerCase());
}

function dobValidate(dob) {
    dob_sp = dob.split("/");
    month = dob_sp[0] - 1;
    day = dob_sp[1];
    year = dob_sp[2];
    var todayDate = new Date(); //Today Date
    var dateOne = new Date(year, month, day);
    if (todayDate < dateOne) {
        return false;
    } else {
        return true;
    }
}
//     $(document).ready(function() {
//     datetimepicker = $(".age").val();
//             var birthyear = datetimepicker.split("/");
//             var splityear = birthyear[2]
//             var current_date = new Date();
//             var year =  current_date.getFullYear();
//             var age = year - splityear;

// });
function isNumber(iKeyCode) {
    // var iKeyCode = (evt.which) ? evt.which : evt.keyCode
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;
    return true;
}
// function isNumber(event) {
//     var key = window.event ? event.keyCode : event.which;
//     if (event.keyCode === 8 || event.keyCode === 46) {
//         return false;
//     } else if ( key < 48 || key > 57 ) {
//         return true;
//     } else {
//         return false;
//     }
// };

npi_status = 0;
// upin_status = 0;
$(document).ready(function() {
    $("#check-pass").on("change", function() {
        var passInput = $(".show-password");
        if (passInput.attr("type") === "password") {
            passInput.attr("type", "text");
            $(".show-psd").text("Hide Password");
        } else {
            passInput.attr("type", "password");
            $(".show-psd").text("Show Password");
        }
    });
    if ($("#nip_number").val() != "") {
        npi_status = checkNpi();
        allowSubmit();
    }
    // if ($("#upin").val() != "") {
    //     upin_status = checkUpin();
    //     allowSubmit();
    // }
    $("#nip_number").keyup(function() {
        npi_status = checkNpi();
        allowSubmit();
    });

    // $("#upin").keyup(function() {
    //     upin_status = checkUpin();
    //     allowSubmit();
    // });

    function checkNpi() {
        npi = $("#nip_number").val();
        npi_status = 0;
        if ((npi != "" && npi.length != 10) || npi.length < 10) {
            $("#nip_number").css("border", "2px red solid");
            $("#npi_err").addClass("d-block");
        } else {
            $("#nip_number").css("border", "1px solid #ccc");
            $("#npi_err").removeClass("d-block");
            npi_status = 1;
        }
        return npi_status;
    }

    // function checkUpin() {
    //     upin = $("#upin").val();
    //     upin_status = 0;
    //     if ((upin != "" && upin.length != 6) || upin.length < 6) {
    //         $("#upin").css("border", "2px red solid");
    //         $("#upin_err").addClass("d-block");
    //     } else {
    //         $("#upin").css("border", "1px solid #ccc");
    //         $("#upin_err").removeClass("d-block");
    //         upin_status = 1;
    //     }
    //     return upin_status;
    // }

    // function allowSubmit() {
    //     if (upin_status == 1 && npi_status == 1) {
    //         $(".submitBtn").prop("disabled", false);
    //         // $(".submitBtn").show();
    //         // $(".noSubmitBtn").hide();
    //     } else {
    //         $(".submitBtn").prop("disabled", true);
    //         // $(".submitBtn").hide();
    //         // $(".noSubmitBtn").show();
    //     }
    // }
});

function get_age() {
    // console.log('user');
    var dob = document.getElementById("datetimepicker").value;
    // dob.toString().substr(-2)
    // console.log(dob);
    var birthyear = dob.split("/");
    var splityear = birthyear[2];
    console.log(splityear);
    var current_date = new Date();
    var year = current_date.getFullYear();
    // year.toString().substr(-2)

    console.log(year);
    var age = year - splityear;
    console.log(age);

    if (age < 18) {
        //    console.log('helloworld') ;
        $("#radio1").prop("checked", false);
        $("#radio2").prop("checked", "checked");
        $("#rep-div").show();
        $("#pat-rep").show();
        $("#radio1").prop("disabled", true);
    } else {
        $("#radio1").prop("disabled", false);
        $("#radio1").prop("checked", "checked");
        $("#pat-rep").hide();
        $("#rep-div").hide();
    }
}

(function() {
    window.requestAnimFrame = (function(callback) {
        return (
            window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            window.oRequestAnimationFrame ||
            window.msRequestAnimaitonFrame ||
            function(callback) {
                window.setTimeout(callback, 1000 / 60);
            }
        );
    })();

    var canvas = document.getElementById("sig-canvas");
    var ctx = canvas.getContext("2d");
    ctx.strokeStyle = "#222222";
    ctx.lineWidth = 1;

    var drawing = false;
    var mousePos = {
        x: 0,
        y: 0,
    };
    var lastPos = mousePos;

    canvas.addEventListener(
        "mousedown",
        function(e) {
            drawing = true;
            lastPos = getMousePos(canvas, e);
        },
        false
    );

    canvas.addEventListener(
        "mouseup",
        function(e) {
            drawing = false;
        },
        false
    );

    canvas.addEventListener(
        "mousemove",
        function(e) {
            mousePos = getMousePos(canvas, e);
        },
        false
    );

    // Add touch event support for mobile
    canvas.addEventListener("touchstart", function(e) {}, false);

    canvas.addEventListener(
        "touchmove",
        function(e) {
            var touch = e.touches[0];
            var me = new MouseEvent("mousemove", {
                clientX: touch.clientX,
                clientY: touch.clientY,
            });
            canvas.dispatchEvent(me);
        },
        false
    );
    var keys = { 37: 1, 38: 1, 39: 1, 40: 1 };

    function preventDefault(e) {
        e.preventDefault();
    }

    function preventDefaultForScrollKeys(e) {
        if (keys[e.keyCode]) {
            preventDefault(e);
            return false;
        }
    }

    var supportsPassive = false;
    try {
        window.addEventListener(
            "test",
            null,
            Object.defineProperty({}, "passive", {
                get: function() {
                    supportsPassive = true;
                },
            })
        );
    } catch (e) {}
    canvas.addEventListener(
        "touchstart",
        function(e) {
            var wheelEvent =
                "onwheel" in document.createElement("div") ?
                "wheel" :
                "mousewheel";
            var wheelOpt = supportsPassive ? { passive: false } : false;
            mousePos = getTouchPos(canvas, e);
            var touch = e.touches[0];
            var me = new MouseEvent("mousedown", {
                clientX: touch.clientX,
                clientY: touch.clientY,
            });
            window.addEventListener("DOMMouseScroll", preventDefault, false); // older FF
            // window.addEventListener(wheelEvent, preventDefault, wheelOpt); // modern desktop
            window.addEventListener("touchmove", preventDefault, wheelOpt); // mobile
            window.addEventListener(
                "keydown",
                preventDefaultForScrollKeys,
                false
            );
            canvas.dispatchEvent(me);
        },
        false
    );

    canvas.addEventListener(
        "touchend",
        function(e) {
            var me = new MouseEvent("mouseup", {});
            canvas.dispatchEvent(me);
        },
        false
    );

    function getMousePos(canvasDom, mouseEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
            x: mouseEvent.clientX - rect.left,
            y: mouseEvent.clientY - rect.top,
        };
    }

    function getTouchPos(canvasDom, touchEvent) {
        var rect = canvasDom.getBoundingClientRect();
        return {
            x: touchEvent.touches[0].clientX - rect.left,
            y: touchEvent.touches[0].clientY - rect.top,
        };
    }

    function renderCanvas() {
        if (drawing) {
            ctx.moveTo(lastPos.x, lastPos.y);
            ctx.lineTo(mousePos.x, mousePos.y);
            ctx.stroke();
            lastPos = mousePos;
        }
    }

    // Prevent scrolling when touching the canvas
    document.body.addEventListener(
        "touchstart",
        function(e) {
            if (e.target == canvas) {
                e.preventDefault();
            }
        },
        false
    );
    document.body.addEventListener(
        "touchend",
        function(e) {
            if (e.target == canvas) {
                e.preventDefault();
            }
        },
        false
    );
    document.body.addEventListener(
        "touchmove",
        function(e) {
            if (e.target == canvas) {
                e.preventDefault();
            }
        },
        false
    );

    (function drawLoop() {
        requestAnimFrame(drawLoop);
        renderCanvas();
    })();

    function clearCanvas() {
        canvas.width = canvas.width;
    }

    // Set up the UI
    // var sigText = document.getElementById("sig-dataUrl");
    var sigImage = document.getElementById("sig-input");
    var clearBtn = document.getElementById("sig-clearBtn");
    // var submitBtn = document.getElementById("submit");
    clearBtn.addEventListener(
        "click",
        function(e) {
            clearCanvas();
            //   sigText.innerHTML = "Data URL for your signature will go here!";
            sigImage.value = "";
        },
        false
    );
})();
