$('.search_lab_btn').click(function(){
    var text=$('#search_lab_text').val();
    var cat_id=$('#lab_cat_id').val();
    var mode='lab-test';

    $.ajax({
        type: "POST",
        url: "/search_lab_item_by_category",
        data: {
            text:text,
            cat_id:cat_id
        },
        success: function(res)
        {
            $('.prescription-req-view-btn').hide();
            $('#load_lab_test_item').html('');
            if(res.products=="" || res.products==null)
            {
                $('#load_lab_test_item').append(
                    '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100">'+
                        '<img src="/assets/images/exclamation.png" alt="">'+
                        '<h1>NO ITEM Found</h1>'+
                        '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                    '</div>'
                );
            }
            else
            {
                if(res.user_id=='')
                {
                    $.each(res.products, function(key, value) {
                        $('#load_lab_test_item').append(
                            '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a  href="/product/labtests/'+value.SLUG+'" class="btn btn-primary view-detail">View Details </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }else{
                    $.each(res.products, function(key, value) {
                        $('#load_lab_test_item').append(
                            '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a  href="/product/labtests/'+value.SLUG+'" class="btn btn-primary view-detail">View Details </a>'+
                                        `<button class="btn btn-primary" type="button" onclick="addToCart(${value.TEST_CD},'${mode}',1)">Add To Cart</button>` +
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }
            }
        }
    });
});


var input = document.getElementById("search_lab_text");
input.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
    var text=$('#search_lab_text').val();
    var cat_id=$('#lab_cat_id').val();
    var mode='lab-test';
        $.ajax({
            type: "POST",
            url: "/search_lab_item_by_category",
            data: {
            text:text,
            cat_id:cat_id
            },
        success: function(res)
        {
                $('.prescription-req-view-btn').hide();
                $('#load_lab_test_item').html('');
            if(res.products=="" || res.products==null)
            {
                    $('#load_lab_test_item').append(
                    '<div class="no-product-text d-flex justify-content-center align-items-center flex-column w-100">'+
                        '<img src="/assets/images/exclamation.png" alt="">'+
                        '<h1>NO ITEM Found</h1>'+
                        '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
            else
            {
                if(res.user_id=='')
                {
                    $.each(res.products, function(key, value) {
                            $('#load_lab_test_item').append(
                            '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a  href="/product/labtests/'+value.SLUG+'" class="btn btn-primary view-detail">View Details </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                                '</div>'
                            );
                        });

                    }
                else
                {
                    $.each(res.products, function(key, value) {
                            $('#load_lab_test_item').append(
                            '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a  href="/product/labtests/'+value.SLUG+'" class="btn btn-primary view-detail">View Details </a>'+
                                        `<button class="btn btn-primary" type="button" onclick="addToCart(${value.TEST_CD},'${mode}',1)">Add To Cart</button>` +
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                                '</div>'
                            );
                        });

                    }


                }
            }
        });
    }
});

function openBeforeModal()
{
    $('#beforeLogin').modal('show');
}


function addToCart(pro_id,pro_mode,pro_qty)
{
    $.ajax({
        type: "POST",
        url: "/add_to_cart",
        data: {
            pro_id: pro_id,
            pro_mode: pro_mode,
            pro_qty: pro_qty,
        },
        success: function(data) {
            if(data.check=='1'){
                $('#alreadyadded').modal('show');
            }
            else{
                console.log('item added into cart');
            }
        },
    });
}

