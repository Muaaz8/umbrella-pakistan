


$('.imagingSearchBtn').click(function(){
    var text=$('#imaging_search_text').val();
    var cat_id=$('#imaging_cat_id').val();
    $.ajax({
        type: "POST",
        url: "/search_imaging_item_by_category",
        data: {
            text:text,
            cat_id:cat_id
        },
        success: function(res)
        {
            $('.prescription-req-view-btn').hide();
            $('#load_imaging_product_search').html('');
            if(res=="" || res==null)
            {
                $('#load_imaging_product_search').append(
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
                    $('#load_imaging_product_search').append(
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
                                            '<a href="/product/imaging/'+value.slug+'"><button>Learn More</button></a>'+
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




var input = document.getElementById("imaging_search_text");
input.addEventListener("keypress", function(event) {
  if (event.key === "Enter") {
    event.preventDefault();
    var text=$('#imaging_search_text').val();
    var cat_id=$('#imaging_cat_id').val();
    $.ajax({
        type: "POST",
        url: "/search_imaging_item_by_category",
        data: {
            text:text,
            cat_id:cat_id
        },
        success: function(res)
        {
            $('.prescription-req-view-btn').hide();
            $('#load_imaging_product_search').html('');
            if(res=="" || res==null)
            {
                $('#load_imaging_product_search').append(
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
                    $('#load_imaging_product_search').append(
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
                                            '<a href="/product/imaging/'+value.slug+'"><button>Learn More</button></a>'+
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





