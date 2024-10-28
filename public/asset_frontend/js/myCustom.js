$(document).ready(function() {
    $("#checkoutForm").submit(function(e) {
        e.preventDefault();

        var form_data = new FormData(this);
        $.ajax({
            url: "/create_order",
            cache: false,
            contentType: false,
            processData: false,
            type: "POST",
            dataType: "html",
            data: form_data,
            beforeSend: function() {
                $(".checkout_loader").show();
            },
            success: function(response) {
                $(".checkout_loader").hide();
                let result = JSON.parse(response);
                if (result.success) {
                    window.location.replace(
                        "/complete_order/" + result.orderID
                    );
                } else {
                    let getErrors = result.paymentStatus.errors;
                    // Card & Expiry Error
                    $(".checkoutErroBox").empty("");
                    $.each(getErrors, function(i, item) {
                        let text = item[0];
                        var alertHTML =
                            `<div class="alert alert-danger"> <strong>Error !!! </strong>` +
                            text +
                            `</div>`;
                        $(".checkoutErroBox").append(alertHTML);
                    });
                    // CSC Error
                    if (
                        typeof result.paymentStatus.approval_message !=
                        "undefined"
                    ) {
                        var alertHTML =
                            `<div class="alert alert-danger"> <strong>Error !!! </strong>` +
                            result.paymentStatus.approval_message +
                            `</div>`;
                        $(".checkoutErroBox").append(alertHTML);
                    }
                }
            },
        });
    });

    $('.shipping_box_toggle input[type="checkbox"]').click(function() {
        if ($(this).prop("checked") == true) {
            $(".ship_hide").hide();
        } else if ($(this).prop("checked") == false) {
            $(".ship_hide").show();
        }
    });


    $(".pagesSearchBarSelect").on("change", function() {
        $(".pagesSearchBarInput").attr("data-category-id", this.value);
    });

    //Open Search
    $(".pagesSearchBarInput").keyup(function(event) {

        $(".instant-results").fadeIn("slow").css("height", "auto");
        event.stopPropagation();
        var query = $(this).val();
        var urlType = $(this).attr("data-type");
        var categoryID = $(this).attr("data-category-id");

        if (query.length >= 1) {
            $.ajax({
                url: "/searchProducts/" +
                    query +
                    "/" +
                    urlType +
                    "/" +
                    categoryID,
                success: function(response) {
                    let result = JSON.parse(response);
                    console.log(result);
                    $(".result-bucket").html("");
                    if(result.total_count > 0) {
                        $.each(result.items, function(i, item) {
                            var modeValue =
                                item.medicine_type == "prescribed" ?
                                item.short_description :
                                item.sale_price;
                            var URL =
                                "/product/" + result.pageName + "/" + item.slug;
                            var str =
                                `<li class="result-entry">
                        <a href="` +
                                URL +
                                `" class="result-link">
                        <div class="media"> <div class="media-left">
                        <img src="/uploads/` +
                                item.featured_image +
                                `" class="media-object"> </div> <div class="media-body">
                        <h4 class="media-heading mb-0">` +
                                item.name +
                                `</h4><p class="mb-0">` +
                                item.main_category_name +
                                `</p> <p class="mb-0">` +
                                modeValue +
                                `</p></div></div></a></li>`;
                            $(".result-bucket").append(str);
                        });
                    } else {
                        var str = `<li class="result-entry"> <h3>No results found</h3> </li>`;
                        $(".result-bucket").append(str);
                    }
                },
            });
        }
    });

    $("body").click(function() {
        $(".instant-results").fadeOut("500");
    });

    // Search Locator

    $(".vendor-picker").change(function() {
        var value = $(this).val();
        if (value == 0) {
            $(".on-select-price").hide();
        } else {
            var str = "<div>" + value + "</div>";
            $(".on-select-price").show();
            $(".on-select-price").empty();
            $(".on-select-price").html(str);
        }
    });
    /*----------------------------------------------------*/
    /*	 Zoom effect for Product page
    /*----------------------------------------------------*/

    $(".single-product-image").zoom();


    function getCartCounter() {
        $.ajax({
            url: "/get_cart_counter",
            type: "GET",
            processData: false,
            contentType: false,
            success: function(response) {
                var result = JSON.parse(response);
                $(".cartCounterShow").html(result);
            },
        });
    }
    getCartCounter();


});

/*----------------------------------------------------*/
/*	 Add To cart
/*----------------------------------------------------*/

function add_to_cart(e, id, mode) {
    let qty = $(".cartSingleProduct #quantity").val();
    let product_id = $(".cartSingleProduct #product_id").val();
    let Link = "";
    if (qty) {
        Link = "/get_cart_content/" + product_id + "/" + qty + "/" + mode;
        //console.log("found");
    } else {
        Link = "/get_cart_content/" + id + "/" + "0" + "/" + mode;
    }

    $.ajax({
        url: Link,
        type: "GET",
        processData: false,
        contentType: false,
        success: function(response) {
            var result = JSON.parse(response);

            // Set Counter
            $(".cartCounterShow").html(result.cart_counter);

            // console.log(result);
            Swal.fire({
                title: result.cart_message,
                // text: "Do you want to continue",
                // icon: "error",
                confirmButtonText: "OK",
            });
            // if (result.status == 1) {
            //     location.reload();
            // }
        },
    });
}

/*----------------------------------------------------*/
/*	 Get Citites by State
/*----------------------------------------------------*/

function getCitiesByStateForBilling(id) {
    let text = $(".states_select_for_billing option:selected").text();

    $(".set_state_name_for_billing").val(text);

    $.ajax({
        url: "get_cities_by_state/" + id,
        type: "GET",
        success: function(response) {
            var res = JSON.parse(response);
            //console.log(res);
            $(".city_select_for_billing").html("");
            res.forEach((item) => {
                let str =
                    `<option value="` +
                    item.name +
                    `" default>` +
                    item.name +
                    `</option>`;
                $(".city_select_for_billing").append(str);
            });
            $(".city_select_for_billing").select2();
        },
    });
}

function getCitiesByStateForShipping(id) {
    let text = $(".states_select_for_shipping option:selected").text();

    $(".set_state_name_for_shipping").val(text);

    $.ajax({
        url: "get_cities_by_state/" + id,
        type: "GET",
        success: function(response) {
            var res = JSON.parse(response);
            console.log(res);

            $(".city_select_for_shipping").html("");
            res.forEach((item) => {
                let str =
                    `<option value="` +
                    item.city +
                    `" default>` +
                    item.city +
                    `</option>`;
                $(".city_select_for_shipping").append(str);
            });
            $(".city_select_for_shipping").select2();
        },
    });
}

function add_to_cart_labtest_alert(e, id) {
    swal(
        "Sorry !!!",
        "You need to prescribe this lab test from a doctor first."
    );
}


$(".btnDialogueLogin").click(function(e) {
    e.preventDefault();
    Swal.fire({
        title: "<strong>Please Login</strong>",
        icon: "info",
        html: "You must be logged in to add to cart",
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: '<a class="text-white" href="/login">Login</a>',
        confirmButtonAriaLabel: "Thumbs up, great!",
        cancelButtonText: '<a class="text-white" href="/patient_register">Register</a>',
        cancelButtonAriaLabel: "Thumbs down",
    });
});

function openCartDialogue() {
    $.ajax({
        type: "GET",
        url: "/getPrescribedProducts",
        success: function(res) {
            let response = res.items;
            //console.log(response);
            if (response.length > 0) {
                $(".dialogueCartList").html("");
                $(".dialogueFooterTotalText").html("");
                $.each(response, function(i, item) {
                    var btn;
                    var short_description;

                    if (item.item_type == "prescribed") {
                        short_description =
                            `<span>Prescribed by Dr.` +
                            item.prescribed_by+
                            `</span>`;
                    } else {
                        short_description = `<span>Online Order</span>`;
                    }

                    if (item.checkout_status == 1) {
                        btn =
                            `<button class="circular ui positive icon button" onclick="updateCheckoutStatus(` +
                            item.product_id +
                            `,0,` +
                            item.cart_row_id +
                            `)"><i class="check icon"></i></button>`;
                    } else {
                        btn =
                            `<button class="circular ui icon button" onclick="updateCheckoutStatus(` +
                            item.product_id +
                            `,1, ` +
                            item.cart_row_id +
                            `)"><i class="close icon"></i></button>`;
                    }

                    var HTML =
                        `<div class="item"><div class="right floated content">` +
                        btn +
                        `</div>
                        <img class="ui avatar image" src="/uploads/` +
                        item.product_image +
                        `">
                    <div class="content">
                    <div class="header"><p>` +
                        item.name +
                        `</p></div>` +
                        short_description +
                        `</div></div>`;

                    $(".dialogueCartList").append(HTML);
                });
                if (res.totalAmount == "0.00") {
                    $(".dialogueFooterBtnCheckout").hide();
                } else {
                    $(".dialogueFooterBtnCheckout").show();
                }
                $(".dialogueFooterTotalText").html(res.totalAmount);
            } else {
                console.log("No Product Found.");
            }
        },
    });
}

function updateCheckoutStatus(product_id, status, cart_row_id) {
    var URL =
        "/updateCheckoutStatus/" +
        product_id +
        "/" +
        status +
        "/" +
        cart_row_id;
    $.ajax({
        type: "GET",
        url: URL,
        success: function(response) {
            //  console.log(response);
            openCartDialogue();
        },
    });
}

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();

    $(".checkout_zipcode").keyup(function(e) {
        var zipcode = $(this).val();
        if (zipcode.length >= 5) {
            $.ajax({
                type: "POST",
                url: "/getCityStateByZipCode",
                data: {
                    zip: zipcode,
                },
                beforeSend: function() {
                    $(".checkout_loader").show();
                },
                success: function(data) {
                    $(".checkout_loader").hide();
                    if (data.country_id == "") {
                        $(".zipCodeErrCheckout").show();
                        $(".states_select_for_billing")
                            .empty()
                            .trigger("change");
                        $(".city_select_for_billing").empty().trigger("change");
                    } else {
                        $(".zipCodeErrCheckout").hide();
                        // console.log(data);
                        $.ajax({
                            type: "POST",
                            url: "/get_states_v2",
                            data: {
                                id: data.state_id,
                            },
                            beforeSend: function() {
                                $(".checkout_loader").show();
                            },
                            success: function(resp) {
                                $(".checkout_loader").hide();
                                if (resp.count > 0) {
                                    $(".zipCodeErrCheckout").hide();
                                    $(".states_select_for_billing")
                                        .empty()
                                        .trigger("change");
                                    $(".city_select_for_billing")
                                        .empty()
                                        .trigger("change");
                                    resp.all.unshift({
                                        id: resp.single.id,
                                        text: resp.single.text,
                                    });
                                    $(".states_select_for_billing").select2({
                                        data: resp.all,
                                    });
                                    getCitiesByStateForBilling(resp.single.id);
                                } else {
                                    $(".zipCodeErrCheckout").show();
                                }
                            },
                        });
                    }
                    // console.log(data.country_id+"="+data.state_id+"="+data.city_id);
                },
            });
        }
    });
});
