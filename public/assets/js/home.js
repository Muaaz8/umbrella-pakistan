$(document).ready(function(){
    $('.imaging-penal').click(function(){
        getImagingProductByCategory('all',6);
    });
    $('.pharmacy-penal').click(function(){
        getPharmacyProductByCategory('all',8);
    });
    $('.labtest-penal').click(function(){
        getLabtestProductByCategory('all',8);
    });
});

function getPharmacyProductByCategory(sub_cat_id,limit)
{
    $.ajax({
        type: "POST",
        url: "/fetch_pharmacy_item_by_category",
        data: {
            sub_cat_id:sub_cat_id,
            limit:limit,
        },
        success: function(res) {
            $('#load_pharmacy_item_by_category').html('');
            $('#load_pharmacy_more_btn').html('');
            if(res=="" || res==null)
                {
                    $('#load_pharmacy_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                $.each(res, function(key, value) {
                    $('#load_pharmacy_item_by_category').append(
                        '<div class="col-md-4 col-lg-3">'+
                            '<div class="required-cards">'+
                                '<div class="card_container prescription-req-div">'+
                                    '<div class="card" data-label="Prescription Required">'+
                                        '<div class="card-container prescription-req-content">'+
                                            '<div class="d-flex align-items-center pt-3">'+
                                                '<i class="fa-solid fa-capsules"></i>'+
                                                '<div class="prescription-req-heading">'+
                                                    '<h3 title="'+value.name+'">'+value.name+'</h3>'+
                                                    '<h6 title="'+value.category_name+'">'+value.category_name+'</h6>'+
                                                '</div>'+
                                            '</div>'+
                                        '<p>'+value.short_description+'</p>'+
                                        '</div>'+
                                        '<div class="prescription-req-btn">'+
                                            '<a href="/medicines/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                });
                if(sub_cat_id=='all')
                {
                    $('#load_pharmacy_more_btn').append(
                        '<a href="/pharmacy"> <button>View All</button></a>'
                    );
                }else{
                    $('#load_pharmacy_more_btn').append(
                        '<a href="/pharmacy/'+res[0].category_slug+'"> <button>View More </button></a>'
                    );
                }
            }
        }
    });
}
function getLabtestProductByCategory(cat_id,limit)
{
    var mode='lab-test';
    $.ajax({
        type: "POST",
        url: "/fetch_labtest_item_by_category",
        data: {
            cat_id:cat_id,
            limit:limit,
        },
        success: function(res) {
            $('#load_labtest_item_by_category').html('');
            $('#load_more_btn_lab').html('');
            if(res.products=="" || res.products==null)
                {
                    $('#load_labtest_item_by_category').append(
                        '<div class="no-product-text">'+
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
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                            );
                        });
                    }
                    else{
                        $.each(res.products, function(key, value) {
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"><h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary '+value.TEST_CD+' '+mode+' 1" type="button" onclick="addedItem(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                            );
                        });
                    }

                    if(cat_id=='all')
                    {
                        $('#load_more_btn_lab').append(
                            '<a href="/labtests"> <button>View All</button></a>'
                        );
                    }else{
                        $('#load_more_btn_lab').append(
                            '<a href="/labtests/'+res[0].cat_name+'"> <button>View More </button></a>'
                        );
                    }
                }
        }
    });
}

function getImagingProductByCategory(cat_id,limit)
{
    $.ajax({
        type: "POST",
        url: "/fetch_imaging_item_by_category",
        data: {
            cat_id:cat_id,
            limit:limit,
        },
        success: function(res) {
            $('#load_imaging_item_by_category').html('');
            $('#load_more_btn_imaging').html('');
            if(res=="" || res==null)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#load_imaging_item_by_category').append(
                            '<div class="col-md-4 mb-3">'+
                                '<div class="medical-imaging-card">'+
                                    '<div class="card">'+
                                        '<div class="content">'+
                                            '<div class="d-flex justify-content-between">'+
                                                '<h5 title="'+value.name+'">'+value.name+'</h5>'+
                                                '<i class="fa-solid fa-circle-radiation"></i>'+
                                            '</div>'+
                                            '<p title="'+value.short_description+'">'+value.short_description+'</p>'+
                                            '<div>'+
                                                '<a href="/imagings/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );

                    });

                    if(cat_id=='all')
                    {
                        $('#load_more_btn_imaging').append(
                            '<a href="/imaging"> <button>View All</button></a>'
                        );
                    }else{
                        $('#load_more_btn_imaging').append(
                            '<a href="/imaging/'+res[0].cat_name+'"> <button>View More </button></a>'
                        );
                    }
                }
        }
    });
}

$('.imagingSearchBtn').click(function(){
    var text=$('#imagingSearchText').val();
    var limit=12;
    if(text.length<4)
    {
        $.ajax({
            type: "POST",
            url: "/search_imaging_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_imaging_item_by_category').html('');
                $('#load_more_btn_imaging').html('');
                if(res=="" || res==null)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                $.each(res, function(key, value)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="col-md-4 mb-3">'+
                            '<div class="medical-imaging-card">'+
                                '<div class="card">'+
                                    '<div class="content">'+
                                        '<div class="d-flex justify-content-between">'+
                                            '<h5 title="'+value.name+'">'+value.name+'</h5>'+
                                            '<i class="fa-solid fa-circle-radiation"></i>'+
                                        '</div>'+
                                        '<p>'+value.short_description+'</p>'+
                                        '<div>'+
                                            '<a href="/imagings/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                });
            }
            }
        });
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "/search_imaging_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_imaging_item_by_category').html('');
                $('#load_more_btn_imaging').html('');
                if(res=="" || res==null)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                $.each(res, function(key, value)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="col-md-4 mb-3">'+
                            '<div class="medical-imaging-card">'+
                                '<div class="card">'+
                                    '<div class="content">'+
                                        '<div class="d-flex justify-content-between">'+
                                            '<h5 title="'+value.name+'">'+value.name+'</h5>'+
                                            '<i class="fa-solid fa-circle-radiation"></i>'+
                                        '</div>'+
                                        '<p>'+value.short_description+'</p>'+
                                        '<div>'+
                                            '<a href="/imagings/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                });
            }
            }
        });
    }
});

var imagingKeySearch = document.getElementById("imagingSearchText");
imagingKeySearch.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var text=$('#imagingSearchText').val();
    var limit=12;
    if(text.length<4)
    {
        $.ajax({
            type: "POST",
            url: "/search_imaging_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_imaging_item_by_category').html('');
                $('#load_more_btn_imaging').html('');
                if(res=="" || res==null)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                $.each(res, function(key, value)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="col-md-4 mb-3">'+
                            '<div class="medical-imaging-card">'+
                                '<div class="card">'+
                                    '<div class="content">'+
                                        '<div class="d-flex justify-content-between">'+
                                            '<h5 title="'+value.name+'">'+value.name+'</h5>'+
                                            '<i class="fa-solid fa-circle-radiation"></i>'+
                                        '</div>'+
                                        '<p>'+value.short_description+'</p>'+
                                        '<div>'+
                                            '<a href="/imagings/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                });
            }
            }
        });
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "/search_imaging_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_imaging_item_by_category').html('');
                $('#load_more_btn_imaging').html('');
                if(res=="" || res==null)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                $.each(res, function(key, value)
                {
                    $('#load_imaging_item_by_category').append(
                        '<div class="col-md-4 mb-3">'+
                            '<div class="medical-imaging-card">'+
                                '<div class="card">'+
                                    '<div class="content">'+
                                        '<div class="d-flex justify-content-between">'+
                                            '<h5 title="'+value.name+'">'+value.name+'</h5>'+
                                            '<i class="fa-solid fa-circle-radiation"></i>'+
                                        '</div>'+
                                        '<p>'+value.short_description+'</p>'+
                                        '<div>'+
                                            '<a href="/imagings/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'
                    );
                });
            }
            }
        });
    }
  }
});

$('.labSearchBtn').click(function(){
    var text=$('#labSearchText').val();
    var limit=12;
    var mode='lab-test';
    if(text.length<4)
    {
        $.ajax({
            type: "POST",
            url: "/search_lab_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_labtest_item_by_category').html('');
                $('#load_more_btn_lab').html('');
                if(res.products=="" || res.products==null)
                {
                    $('#load_labtest_item_by_category').append(
                        '<div class="no-product-text">'+
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
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                  '<div class="card d-flex align-items-center justify-content-center">'+
                                      '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                      '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                      '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                      '<p>'+value.DETAILS+'</p>'+
                                      '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                      '</div>'+
                                  '</div>'+
                                '</div>'+
                              '</div>'
                            );
                        });
                    }
                    else{
                        $.each(res.products, function(key, value) {
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                  '<div class="card d-flex align-items-center justify-content-center">'+
                                      '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                      '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                      '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                      '<p>'+value.DETAILS+'</p>'+
                                      '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary '+value.TEST_CD+' '+mode+' 1" type="button" onclick="addedItem(this)">Add To Cart</button>'+
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
    else
    {
        $.ajax({
            type: "POST",
            url: "/search_lab_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_labtest_item_by_category').html('');
                $('#load_more_btn_lab').html('');
                if(res.products=="" || res.products==null)
                {
                    $('#load_labtest_item_by_category').append(
                        '<div class="no-product-text">'+
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
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                            );
                        });
                    }
                    else{
                        $.each(res.products, function(key, value) {
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary '+value.TEST_CD+' '+mode+' 1" type="button" onclick="addedItem(this)">Add To Cart</button>'+
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

var labKeySearch = document.getElementById("labSearchText");
labKeySearch.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var text=$('#labSearchText').val();
    var limit=12;
    var mode='lab-test';
    if(text.length<4)
    {
        $.ajax({
            type: "POST",
            url: "/search_lab_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_labtest_item_by_category').html('');
                $('#load_more_btn_lab').html('');
                if(res.products=="" || res.products==null)
                {
                    $('#load_labtest_item_by_category').append(
                        '<div class="no-product-text">'+
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
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                            );
                        });
                    }
                    else{
                        $.each(res.products, function(key, value) {
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary '+value.TEST_CD+' '+mode+' 1" type="button" onclick="addedItem(this)">Add To Cart</button>'+
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
    else
    {
        $.ajax({
            type: "POST",
            url: "/search_lab_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_labtest_item_by_category').html('');
                $('#load_more_btn_lab').html('');
                if(res.products=="" || res.products==null)
                {
                    $('#load_labtest_item_by_category').append(
                        '<div class="no-product-text">'+
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
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary" type="button" onclick="openBeforeModal(this)">Add To Cart</button>'+
                                    '</div>'+
                                '</div>'+
                                '</div>'+
                            '</div>'
                            );
                        });
                    }
                    else{
                        $.each(res.products, function(key, value) {
                            $('#load_labtest_item_by_category').append(
                                '<div class="col-lg-3 col-md-6 mt-lg-0 my-5">'+
                                '<div class="add-to-cart-card">'+
                                '<div class="card d-flex align-items-center justify-content-center">'+
                                    '<div class="ribon"> <span class="fa-solid fa-flask"></span> </div>'+
                                    '<div class="add-to-cart-card-head"> <h4 class="h-1 pt-5" title="'+value.TEST_NAME+'">'+value.TEST_NAME+'</h4></div>'+
                                    '<span class="price"> <sup class="sup">$</sup> <span class="number">'+value.SALE_PRICE+'</span> </span>'+
                                    '<p>'+value.DETAILS+'</p>'+
                                    '<div class="add-cart-btn-div">'+
                                        '<a class="btn btn-primary view-detail" href="/labtest/'+value.SLUG+'" title="View Details"> View Details  </a>'+
                                        '<button class="btn btn-primary '+value.TEST_CD+' '+mode+' 1" type="button" onclick="addedItem(this)">Add To Cart</button>'+
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
  }
});


$('.searchPharmacyProduct').click(function(){
    var text=$('#pharmacySearchText').val();
    var limit=12;
    if(text.length<4)
    {
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_pharmacy_item_by_category').html('');
                $('#load_pharmacy_more_btn').html('');
                if(res=="" || res==null)
                {
                    $('#load_pharmacy_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#load_pharmacy_item_by_category').append(
                            '<div class="col-md-3">'+
                                '<div class="required-cards">'+
                                    '<div class="card_container prescription-req-div">'+
                                        '<div class="card" data-label="Prescription Required">'+
                                            '<div class="card-container prescription-req-content">'+
                                                '<div class="d-flex align-items-center pt-3">'+
                                                    '<i class="fa-solid fa-capsules"></i>'+
                                                    '<div class="prescription-req-heading">'+
                                                        '<h3 title="'+value.name+'">'+value.name+'</h3>'+
                                                        '<h6 title="'+value.category_name+'">'+value.category_name+'</h6>'+
                                                    '</div>'+
                                                '</div>'+
                                            '<p>'+value.short_description+'</p>'+
                                            '</div>'+
                                            '<div class="prescription-req-btn">'+
                                                '<a href="/medicines/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }
            }
        });
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res) {
                $('#load_pharmacy_item_by_category').html('');
                $('#load_pharmacy_more_btn').html('');
                if(res=="" || res==null)
                {
                    $('#load_pharmacy_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#load_pharmacy_item_by_category').append(
                            '<div class="col-md-3">'+
                                '<div class="required-cards">'+
                                    '<div class="card_container prescription-req-div">'+
                                        '<div class="card" data-label="Prescription Required">'+
                                            '<div class="card-container prescription-req-content">'+
                                                '<div class="d-flex align-items-center pt-3">'+
                                                    '<i class="fa-solid fa-capsules"></i>'+
                                                    '<div class="prescription-req-heading">'+
                                                        '<h3>'+value.name+'</h3>'+
                                                        '<h6>'+value.category_name+'</h6>'+
                                                    '</div>'+
                                                '</div>'+
                                            '<p>'+value.short_description+'</p>'+
                                            '</div>'+
                                            '<div class="prescription-req-btn">'+
                                                '<a href="/medicines/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });

                }


            }
        });
    }
});

var pharmacyKeySearch = document.getElementById("pharmacySearchText");
pharmacyKeySearch.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var text=$('#pharmacySearchText').val();
    var limit=12;
    if(text.length<4)
    {
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res)
            {
                $('#load_pharmacy_item_by_category').html('');
                $('#load_pharmacy_more_btn').html('');
                if(res=="" || res==null)
                {
                    $('#load_pharmacy_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#load_pharmacy_item_by_category').append(
                            '<div class="col-md-3">'+
                                '<div class="required-cards">'+
                                    '<div class="card_container prescription-req-div">'+
                                        '<div class="card" data-label="Prescription Required">'+
                                            '<div class="card-container prescription-req-content">'+
                                                '<div class="d-flex align-items-center pt-3">'+
                                                    '<i class="fa-solid fa-capsules"></i>'+
                                                    '<div class="prescription-req-heading">'+
                                                        '<h3 title="'+value.name+'">'+value.name+'</h3>'+
                                                        '<h6 title="'+value.category_name+'">'+value.category_name+'</h6>'+
                                                    '</div>'+
                                                '</div>'+
                                            '<p>'+value.short_description+'</p>'+
                                            '</div>'+
                                            '<div class="prescription-req-btn">'+
                                                '<a href="/medicines/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });
                }
            }
        });
    }
    else
    {
        $.ajax({
            type: "POST",
            url: "/search_pharmacy_item",
            data: {
                text:text,
                limit:limit,
            },
            success: function(res) {
                $('#load_pharmacy_item_by_category').html('');
                $('#load_pharmacy_more_btn').html('');
                if(res=="" || res==null)
                {
                    $('#load_pharmacy_item_by_category').append(
                        '<div class="no-product-text">'+
                            '<img src="/assets/images/exclamation.png" alt="">'+
                            '<h1>NO ITEM Found</h1>'+
                            '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                        '</div>'
                    );
                }
                else
                {
                    $.each(res, function(key, value) {
                        $('#load_pharmacy_item_by_category').append(
                            '<div class="col-md-3">'+
                                '<div class="required-cards">'+
                                    '<div class="card_container prescription-req-div">'+
                                        '<div class="card" data-label="Prescription Required">'+
                                            '<div class="card-container prescription-req-content">'+
                                                '<div class="d-flex align-items-center pt-3">'+
                                                    '<i class="fa-solid fa-capsules"></i>'+
                                                    '<div class="prescription-req-heading">'+
                                                        '<h3>'+value.name+'</h3>'+
                                                        '<h6>'+value.category_name+'</h6>'+
                                                    '</div>'+
                                                '</div>'+
                                            '<p>'+value.short_description+'</p>'+
                                            '</div>'+
                                            '<div class="prescription-req-btn">'+
                                                '<a href="/medicines/'+value.slug+'" title="Learn More"><button>Learn More</button></a>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                        );
                    });

                }


            }
        });
    }
  }
});

function openBeforeModal()
{

    $('#beforeLogin').modal('show');
}


function addedItem(a)
{
    var all_classes=$(a).attr('class');
    var class_split = all_classes.split(' ');
    var pro_id=class_split[2];
    var pro_mode=class_split[3];
    var pro_qty=class_split[4];
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