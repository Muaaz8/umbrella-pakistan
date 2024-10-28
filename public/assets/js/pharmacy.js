


$('.searchPharmacyProduct').click(function(){
    var text=$('#pharmacySearchText').val();
    var cat_id=$('#pharmacy_cat_name').val();
    $.ajax({
        type: "POST",
        url: "/search_pharmacy_item_by_category",
        data: {
            text:text,
            cat_id:cat_id
        },
        success: function(res)
        {
            $('.prescription-req-view-btn').hide();
            $('#loadSearchPharmacyItemByCategory').html('');
            if(res=="" || res==null)
            {
                $('#loadSearchPharmacyItemByCategory').append(
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
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="col-md-4 col-lg-3 col-sm-6 col-11 resp-phar-col">'+
                            '<div class="required-cards">'+
                                '<div class="card_container prescription-req-div">'+
                                    '<div class="card" data-label="Prescription Required">'+
                                        '<div class="card-container prescription-req-content">'+
                                            '<div class="d-flex pt-3">'+
                                                '<i class="fa-solid fa-capsules"></i>'+
                                                '<div class="prescription-req-heading">'+
                                                '<h3 title="'+value.name+'">'+value.name+'</h3>'+
                                                '<h6 title="'+value.category_name+'">'+value.category_name+'</h6>'+
                                                '</div>'+
                                            '</div>'+
                                            '<p>'+value.short_description+'</p>'+
                                        '</div>'+
                                        '<div class="prescription-req-btn">'+
                                            '<a href="/product/pharmacy/'+value.slug+'"><button>Learn More</button></a>'+
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
});


var input = document.getElementById("pharmacySearchText");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var text=$('#pharmacySearchText').val();
    var cat_id=$('#pharmacy_cat_name').val();
    $.ajax({
        type: "POST",
        url: "/search_pharmacy_item_by_category",
        data: {
            text:text,
            cat_id:cat_id
        },
        success: function(res)
        {
            $('.prescription-req-view-btn').hide();
            $('#loadSearchPharmacyItemByCategory').html('');
            if(res=="" || res==null)
            {
                $('#loadSearchPharmacyItemByCategory').append(
                    '<div class="no-product-text py-4">'+
                        '<img src="/assets/images/exclamation.png" alt="">'+
                        '<h1>NO ITEM Found</h1>'+
                        '<p>There are no item that match your current filters. Try removing some of them to get better results.</p>'+
                    '</div>'
                );

            }
            else
            {
                $.each(res, function(key, value) {
                    $('#loadSearchPharmacyItemByCategory').append(
                        '<div class="col-md-4 col-lg-3 col-sm-6 col-11 resp-phar-col">'+
                            '<div class="required-cards">'+
                                '<div class="card_container prescription-req-div">'+
                                    '<div class="card" data-label="Prescription Required">'+
                                        '<div class="card-container prescription-req-content">'+
                                            '<div class="d-flex pt-3">'+
                                                '<i class="fa-solid fa-capsules"></i>'+
                                                '<div class="prescription-req-heading">'+
                                                    '<h3 title="'+value.name+'">'+value.name+'</h3>'+
                                                    '<h6 title="'+value.category_name+'">'+value.category_name+'</h6>'+
                                                '</div>'+
                                            '</div>'+
                                            '<p>'+value.short_description+'</p>'+
                                        '</div>'+
                                        '<div class="prescription-req-btn">'+
                                            '<a href="/product/pharmacy/'+value.slug+'"><button>Learn More</button></a>'+
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
