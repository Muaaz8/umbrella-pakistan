<!-- dialogueBoxCart -->
<div id="dialogueBoxCart" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- dialogueBoxCart content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">PROCEEDING TO CHECKOUT <br> <small>You can also buy a few of them prescribed
                        products</small> </h4> <button type="button" class="close"
                    data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="ui large middle aligned divided list dialogueCartList"></div>
            </div>
            <div class="modal-footer" style=" justify-content: space-between; ">
                <div class="dialogueFooterTotal">
                    <p style=" font-size: 22px; font-weight: 500; "><small>Total: $<span class="dialogueFooterTotalText">0.00</span> </small></p>
                </div>
                <div class="dialogueFooterBtn">
                    <a href="/checkout" type="button"
                        style="background-color:#08295a;color:white;font-size: 1rem;padding: 10px 20px;"
                        class="btn btn-default dialogueFooterBtnCheckout">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- dialogueBoxCart -->
<p class='d-none' id='countNote'></p>
<p class='d-none' id='tost'></p>
<p class='d-none' id='loadPatient'></p>
{{-- <span class='zoom' id='ex1'>
    <img src='https://semantic-ui.com/images/wireframe/image.png' width='555' height='320' alt='Daisy on the Ohoopee' />
    <p>Hover</p>
</span> --}}
<script src="{{ asset('asset_frontend/js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/bootstrap.min.js') }}"></script>

<script>





    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#searchdata").keyup(function() {
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'PKR',
            minimumFractionDigits: 2
        })

        value = $("#searchdata").val();
        var cc = value.length;
        if (cc <= 0) {
            $(".search-flydown").css('display', 'none');
        } else {
            $(".search-flydown").css('display', 'block');
        }

        $.post("{{ route('mainSearch') }}", {
            "value": value
        }, function(success) {


            var len = 0;
            if (success['medison'] != null) {
                len = success['medison'].length;
            }
            if (len > 0) {

                $("#loadSearch").html("");
                for (var i = 0; i < len; i++) {
                    var id = success['medison'][i].id;
                    var name = success['medison'][i].name;
                    var slug = success['medison'][i].slug;
                    var mode = success['medison'][i].mode;
                    var sale_price = success['medison'][i].sale_price;
                    var featured_image = success['medison'][i].featured_image;
                    var regular_price = success['medison'][i].regular_price;
                    var tr_str =
                        "<a class='search-flydown--product search-flydown--product' href='/product/pharmacy/" +
                        slug + "'> <div class='search-flydown--product-image'> <img src='" +
                        featured_image + "' alt='" + name + "' data-rimg='' srcset='" + featured_image +
                        "'> </div> <div class='search-flydown--product-text'> <span class='search-flydown--product-title'>" +
                        name +
                        "</span> <span class='search-flydown--product-price search-flydown--product-price-has-sale'> <span class='search-flydown--product-price--main'> <span class='money'>" +
                        formatter.format(sale_price) +
                        "</span> </span> <span class='search-flydown--product-price--compare-at'> <span class='money'>" +
                        formatter.format(regular_price) + "</span></span></span></div></a>";
                    $("#loadSearch").append(tr_str);
                }
            }

        }, 'json');

    });
</script>
<script src="{{ asset('asset_frontend/js/modernizr.custom.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.easing.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.appear.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.stellar.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/menu.js') }}"></script>
{{-- <script src="{{ asset('asset_frontend/js/sticky.js') }}"></script> --}}
<script src="{{ asset('asset_frontend/js/jquery.scrollto.js') }}"></script>
<script src="{{ asset('asset_frontend/js/materialize.js') }}"></script>
<script src="{{ asset('asset_frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/hero-form.js') }}"></script>
<script src="{{ asset('asset_frontend/js/contact-form.js') }}"></script>
<script src="{{ asset('asset_frontend/js/comment-form.js') }}"></script>
<script src="{{ asset('asset_frontend/js/appointment-form.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.datetimepicker.full.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('asset_frontend/js/wow.js') }}"></script>
<script src="{{ asset('asset_frontend/js/jquery.zoom.js') }}"></script>
<script src="{{ asset('asset_frontend/js/notify.min.js') }}"></script>
<!-- Custom Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"
integrity="sha512-dqw6X88iGgZlTsONxZK9ePmJEFrmHwpuMrsUChjAw1mRUhUITE5QU9pkcSox+ynfLhL15Sv2al5A0LVyDCmtUw=="
crossorigin="anonymous"></script>
<script src="{{ asset('asset_frontend/js/custom.js') }}"> </script>
<script type="text/javascript" src="{{ asset('asset_frontend/js/myCustom.js') }}"></script>
{{-- <script type="text/javascript" src="{{ asset('asset_frontend/js/pharmacy_map_billing.js') }}"></script> --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    new WOW().init();
</script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
Echo.channel('events')
    .listen('RealTimeMessage',(e)=> {
        var user_id={{ Auth::user()->id ?? '0'}};
        if(e.user_id==user_id)
        {
            if(e.getNote!='' || e.getNote!=null)
            {
                $('#noteData').html('');

                $.each (e.getNote, function (key, note) {

                    var today = new Date();

                    var Christmas = new Date(note.created_at);

                    var diffMs = (today-Christmas); // milliseconds between now & Christmas
                    var diffDays = Math.floor(diffMs / 86400000); // days
                    var diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
                    var diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
                    var noteTime='';

                    if(diffDays<=0)
                    {

                        if(diffHrs<=0)
                        {
                            if(diffMins<=0)
                            {
                                noteTime='0 mint ago';
                            }
                            else
                            {
                                noteTime=diffMins+' mints ago';
                            }
                        }
                        else
                        {
                            noteTime=diffHrs +' hours ago';
                        }
                    }
                    else{

                        if(diffDays==1)
                        {
                            noteTime=diffDays+' day ago';
                        }else{
                            noteTime=diffDays+' day ago';
                        }
                    }
                    if(note.status=='new')
                    {
                        $('#noteData').append(
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class="sec_ newNotifications" style="background-color: #fcfafa !important;">'+
                                    '<span class="nav_notification float-right">New</span>'+
                                    '<div class="txt_ not_text">'+note.text+'</div>'+
                                    '<div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> '+noteTime+'</div>'+
                                '</div>'+
                            '</a>'
                        );

                        $('#notif').append(
                          '<div class = "sec new">'
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class = "profCont">'+
                                    '<img class = "profile" src = "{{asset("assets/images/logo.png")}}">'+
                                  '</div>'+
                                  '<div class="txt">'+note.text+'</div>'+
                                  '<div class = "txt sub">'+noteTime+'</div>'+
                            '</a>'
                          '</div>'
                        );
                    }
                    else
                    {
                        $('#noteData').append(
                            '<a href="/ReadNotification/'+note.id+'">'+
                                '<div class="sec_  oldNotifications" style=" background-color: #ffffff !important;">'+
                                    '<span class="nav_notification_seen float-right">Seen</span>'+
                                    '<div class="txt_ not_text">'+note.text+'</div>'+
                                    '<div class="txt_ not_subtext"><i class="zmdi zmdi-time"></i> '+noteTime+'</div>'+
                                '</div>'+
                            '</a>'
                        );

                        $('#notif').append(
                          '<div class = "sec">'
                            '<a href="/ReadNotification/'+note.id+'" >'+
                                '<div class = "profCont">'+
                                    '<img class = "profile" src = "{{asset("assets/images/umbrella_white.png")}}">'+
                                  '</div>'+
                                  '<div class="txt">'+note.text+'</div>'+
                                  '<div class = "txt sub">'+noteTime+'</div>'+
                            '</a>'
                          '</div>'
                        );
                    }

                });
            }
            if(e.countNote!='' || e.countNote!=null)
            {
                if(e.countNote>10)
                {
                    $('#countNote').text('10+');
                }else{
                    $('#countNote').text(e.countNote);
                }
            }
            if(e.toastShow!='' || e.toastShow!=null)
            {
                $.each (e.toastShow, function (key, toast) {
                    $.notify(
                        {
                            title: "<strong>1 New Notification</strong>",
                            message: "<br>"+toast.text,
                            icon: 'fas fa-bell',
                        },
                        {
                            type: "info",
                            allow_dismiss: true,
                            delay: 3000,
                            placement: {
                            from: "bottom",
                            align: "right"
                            },
                        }
                    );
                });

            }
        }
});

function myFunction() {
    $.ajax({
                type: "POST",
                url: "/ReadAllNotifications",
                data: {
                    check: "",
                },
                success: function(data) {

                },
            });
}

Echo.channel('count_user_cart_item')
    .listen('CountCartItem', (e) => {
    var user_id = {{ Auth::user()->id ?? '0' }};
    if(e.user_id==user_id)
    {
        $('.cartCounterShow').text(e.cart_conunt);
    }
});
</script>
