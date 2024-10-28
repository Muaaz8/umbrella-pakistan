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
