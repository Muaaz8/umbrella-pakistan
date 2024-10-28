@extends('layouts.frontend')

@section('content')
    <div class="cart-page base-color pb-3" style="border-top:1px solid #08295a" id="content">
        <div class="container">
            <div class="row mt-5 mb-5 cartDynamicContent">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        jQuery(document).ready(function() {
            $.ajax({
                type: "GET",
                url: "/getUserCartData",
                dataType: "html",
                beforeSend: function() {
                   // console.log("Please wait");
                },
                success: function(response) {
                    $('.cartDynamicContent').html(response)
                    //   console.log(response)
                }
            });
        });
    </script>
@endsection
