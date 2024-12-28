@if (!empty($forCart['cartItems']))
    <div class="col-md-8">
        <div class="card border">
            <h6 class="text-capitalized bg-heading px-3 py-3 mb-0 cart_head">
                Shopping Cart</h6>

            <div class="py-0 px-2 cart-wrapper">
                {{-- Cart Form --}}
                <form id="cart_list_product">
                    <ul class="cart_list_product_ul m-0">
                        @forelse ($forCart['cartItems'] as $item)
                            <li>
                                <div class="bdge-category" style="margin: 0px 0px 20px 0px;">
                                    @if ($item['product_mode'] == 'medicine')
                                        <a class="ui small red tag label">Pharmacy</a>
                                        @if ($item['item_type'] == 'prescribed')
                                            <a class="ui teal tag label float">Prescribed</a>
                                        @else
                                            <a class="ui green tag label float">Online</a>
                                        @endif

                                    @elseif($item['product_mode'] == 'lab-test')
                                        <a class="ui small purple tag label">Lab Test</a>
                                        @if ($item['item_type'] == 'prescribed')
                                            <a class="ui teal tag label float">Prescribed</a>
                                        @else
                                            <a class="ui green tag label float">Online</a>
                                        @endif
                                    @elseif($item['product_mode'] == 'imaging')
                                        <a class="ui small orange tag label">Imaging</a>
                                        @if ($item['item_type'] == 'prescribed')
                                            <a class="ui teal tag label float">Prescribed</a>
                                        @else
                                            <a class="ui green tag label float">Online</a>
                                        @endif
                                    @endif
                                </div>

                                <div class="left-cart row">
                                    <div class="col-2 p-0">
                                        <img class="img-responsive col-12 p-0  prod_img"
                                            src="{{ url('/uploads/' . $item['product_image']) }}"
                                            alt="{{ $item['name'] }}">
                                    </div>
                                    <div class="col-10">
                                        <p class="always-show">{{ $item['name'] }} </p>
                                        @if ($item['item_type'] == 'prescribed')
                                            @if (!empty($item['medicine_usage']) && $item['product_mode'] != 'lab-test')
                                                <p class="cart_subtitles">{{ $item['medicine_usage'] }}
                                                </p>
                                            @endif
                                            @isset($item['prescribed_by'])
                                                <p class="cart_subtitles">Prescribed:
                                                    Dr.{{ $item['prescribed_by'] }} - {{  Carbon\Carbon::parse($item['prescription_date'])->format('m/d/Y H:i:s'); }}
                                                </p>
                                            @endisset
                                        @endif
                                        @if ($item['product_mode'] == 'lab-test' && $item['item_type'] == 'counter')
                                            <p class="cart_subtitles"> +$6.00 Provider's Fee</p>
                                        @endif
                                        <div class="ui mini quantity-message message hidden red">
                                            <i class="shipping fast icon"></i>Selected quantity is not eligible for
                                            fast!
                                        </div>
                                        @if ($item['item_type'] == 'counter')
                                            <a href="#" class="cross quick-fix remove_cart_items" product_id={{ $item['product_id'] }} cart_row_id={{ $item['cart_row_id'] }} style="color:grey"><i class="fa fa-trash"></i> Remove</a>
                                        @endif
                                    </div>
                                </div>

                                <div class="right-cart">
                                    <h4 id="cart_product_id_1" class="font-weight-bold">
                                        Rs. {{ number_format($item['price'], 2) }}</h4>
                                    {{-- @if ($item['quantity'] > 1)
                                        <h4 class="cart_product" id="cart_product_id_1_cut">
                                            Total: Rs. {{ number_format($item['update_price'], 2) }} </h4>
                                    @endif --}}

                                    @if ($item['item_type'] != 'prescribed')
                                        @if ($item['product_mode'] != 'lab-test')
                                            {{-- <div class="quantity">
                                                <input product_id={{ $item['product_id'] }}
                                                    cart_row_id={{ $item['cart_row_id'] }}
                                                    class="product_quantity_input" readonly type="number"
                                                    min="1" max="10" step="1"
                                                    value="{{ $item['quantity'] }}">
                                            </div> --}}
                                            <input class="readonlyQty2" readonly type="number"
                                                value="{{ $item['quantity'] }}">
                                        @else
                                            <input class="readonlyQty2" readonly type="number"
                                                value="{{ $item['quantity'] }}">
                                        @endif
                                    @else
                                        <input class="readonlyQty2" readonly type="number"
                                            value="{{ $item['quantity'] }}">
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="font-weight-bold no_item">
                                No Items Added
                            </li>
                        @endforelse
                    </ul>
                    <br clear="all">
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border">
            <h6 class="text-capitalize bg-heading px-3 py-3 mb-0 order_summ">
                Order Summary</h6>
            <div class="py-0 px-0">

                <div class="cart-right-wrapper">

                    <!--Cart Total-->
                    <form method="post" id="cart_list_product">
                        <div class="cart-total-wrap" style="border-radius:0px">
                            <div class="cart-total mb-0 pt-1 px-0 pb-0">
                                <ul>
                                    <li class="border-bottom">Total Cost</li>
                                    <li class="border-bottom" id="total_product_cost">
                                        Rs. {{ isset($forCart['itemTotal']) ? number_format($forCart['itemTotal'], 2) : '00.00' }}
                                    </li>
                                    {{-- <li class="border-bottom">Shipping Tax</li>
                                    <li class="border-bottom" id="shipping_cost">$200.00</li> --}}
                                    <li class="border-bottom">To be Paid</li>
                                    <li class="border-bottom font-weight-bold" style="font-size:20px"
                                        id="grand_total_amount">
                                        Rs. {{ isset($forCart['itemTotal']) ? number_format($forCart['itemTotal'], 2) : '00.00' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 p-0">
            @if ($forCart['prescribed_count'] > 0)
                <button class="btn mt-2 col-12 abc123 proceed" onclick="openCartDialogue()" id="continue_shopping"
                    data-toggle="modal" data-target="#dialogueBoxCart">PROCEED TO CHECKOUT</button>
            @else
                <a href="/checkout" class="btn mt-2 col-12 abc123 proceed">
                    Proceed To Checkout</a>
            @endif
        </div>
    @else
        <div class="col">
            <h2 class="text-center">Your Umbrellamd Cart is empty</h2>
        </div>
@endif



<script>
    function dynamicRefresh() {
        $.ajax({
            type: "GET",
            url: "/getUserCartData",
            dataType: "html",
            beforeSend: function() {
                //console.log("Please wait");
            },
            success: function(response) {
                $('.cartDynamicContent').html(response)
            }
        });
    }

    $(".product_quantity_input").change(function() {
        var txtVal = this.value;
        $.ajax({
            url: "/update_cart_item_quantity/" +
                $(this).attr("product_id") +
                "/" +
                txtVal +
                "?cart_row_id=" + $(this).attr("cart_row_id"),
            type: "GET",
            processData: false,
            contentType: false,
            success: function(response) {
                let result = JSON.parse(response);
                console.log(result);
                if (result.status == 1) {
                    dynamicRefresh()
                } else {
                    Swal.fire({
                        title: "Error",
                        text: "Quantity Exceeded. Total remaining quantity is " +
                            result.remaining,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                }
            },
        });
    });

    $(".remove_cart_items").click(function(e) {

        e.preventDefault();

        var product_id = $(this).attr("product_id");
        var cart_row_id = $(this).attr("cart_row_id");

        $.ajax({
            url: "/remove_single_cart_item/" + product_id + "/" + cart_row_id,
            type: "GET",
            processData: false,
            contentType: false,
            success: function(response) {
                let result = JSON.parse(response);
                //console.log(result);
                if (result.status == 1) {

                    dynamicRefresh();

                }
            },
        });
    });

    jQuery(
            '<div class="quantity-nav"><div class="quantity-button quantity-up">+</div><div class="quantity-button quantity-down">-</div></div>'
        )
        .insertAfter(".quantity input");

    jQuery(".quantity").each(function() {
        var spinner = jQuery(this),
            input = spinner.find('input[type="number"]'),
            btnUp = spinner.find(".quantity-up"),
            btnDown = spinner.find(".quantity-down"),
            min = input.attr("min"),
            max = input.attr("max");

        btnUp.click(function() {
            var oldValue = parseFloat(input.val());
            if (oldValue >= max) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue + 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
        });

        btnDown.click(function() {
            var oldValue = parseFloat(input.val());
            if (oldValue <= min) {
                var newVal = oldValue;
            } else {
                var newVal = oldValue - 1;
            }
            spinner.find("input").val(newVal);
            spinner.find("input").trigger("change");
        });
    });
</script>
