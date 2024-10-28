<style type="text/css">
    #local-player {
        height: 100%;
    }

    #remote-playerlist {
        height: 100%;
    }

#step_one:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.2);


}
#pack_one:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.1);


}
#pack_two:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.05);


}
#pack_three:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.1);


}
#step_two:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.2);


}
#step_three:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.2);


}
#step_four:hover{

        transition: all .2s ease-in-out;

          transform: scale(1.2);


}

    .doctorDiv1 {
        height: 100%;
        background-color: royalblue;
    }

    .doctorDiv2 {
        bottom: 0px;
        position: absolute;
        height: 35%;
        background-color: pink;
    }

    .patientDiv1 {
        height: 100%;
        background-color: royalblue;
    }

    .patientDiv2 {
        bottom: 0px;
        position: absolute;
        height: 35%;
        background-color: pink;
    }

    .mainClass {
        height: 660px;
        background-color: salmon;
    }

    .andcallBTN {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 5%;
    }

    #radioBtn .notActive {
        color: #08295a;
        background-color: #fff;
        font-size: 18px;
        border: solid 1px #08295a;
        /* padding: 10% 64% */
    }

    #radioBtn .active {
        border: solid 1px #08295a;
        color: #fff;
        background-color: #08295a;
        font-size: 18px;
        /* padding: 10% 64% */
    }

    #sidebar {
        /* width: 20%; */
        /* left: 0; */
        background: #8288a0;
        color: #fff;
        padding-right: 0
    }

    #sidebar p {
        font-family: Poppins, sans-serif;
        font-size: 1.1em;
        font-weight: 700;
        line-height: 1.7em;
        color: #999
    }

    /* #sidebar a,
    a:focus,
    a:hover {
        color: inherit;
        text-decoration: none;
        transition: all .3s;
        font-weight: 700
    } */

    #sidebar a[data-toggle=collapse] {
        position: relative
    }

    .dropdown-toggle::after {
        display: block;
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%)
    }

    #sidebar {
        background: #08295a;
        color: #fff;
        transition: all .3s;
        /* width: 250px */
    }

    #sidebar .sidebar-header {
        padding: 20px 20%;
        background: #821515;
        /* width: 100%; */
        margin-right: 0
    }

    #sidebar .sidebar-header h3 {
        color: #fff;
        margin-right: 0
    }

    #sidebar ul.components {

        border-bottom: 1px solid #08295a
    }

    #sidebar ul p {
        color: #fff;
        background-color: #ad0d0df6;
        padding: 10px
    }

    #sidebar ul li a {
        padding: 10px;
        font-size: 1.1em;
        display: block
    }

    #sidebar ul li a:hover {
        color: #08295a;
        background: #fff
    }

    #sidebar ul li.active>a,
    a[aria-expanded=true] {
        color: #fff;
        background: #d0d0d0;
    }

    #sidebar ul ul a {
        font-size: .9em !important;
        padding-left: 30px !important;
        background: #08295a;
        color: #fff;
    }

    #content {
        border-top: 1px #821515 solid
    }

    #details {
        width: 1110px
    }

    .product-item-box {
        max-width: 100%;
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        width: 290px;
        min-height: 0;
        background: #fff;
        padding: 0;
        border: none;
        border-radius: .28571429rem;
        -webkit-box-shadow: 0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
        box-shadow: 0 1px 3px 0 #d4d4d5, 0 0 0 1px #d4d4d5;
        -webkit-transition: -webkit-box-shadow .1s ease, -webkit-transform .1s ease;
        transition: -webkit-box-shadow .1s ease, -webkit-transform .1s ease;
        transition: box-shadow .1s ease, transform .1s ease;
        transition: box-shadow .1s ease, transform .1s ease, -webkit-box-shadow .1s ease, -webkit-transform .1s ease;
        z-index: ''
    }

    .reduce-price {
        background: green;
        border-radius: 0 0 1px 95%;
        box-shadow: 2px 2px 2px #ccc;
        color: #fff;
        height: 55px;
        position: absolute;
        right: 0;
        top: 0;
        width: 75px;
        z-index: 1
    }

    .goBackDesktop {
        float: right;
        position: absolute;
        top: 35%;
        right: 2.5%;
        font-size: 1.2rem;
        background: #08295a;
        box-sizing: border-box;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .card-hover-effect:hover {
        box-shadow: 0 0 21px rgb(33 33 33 / 50%) !important;
    }

    .reduce-price span {
        display: block;
        font-size: 11px;
        font-weight: 400;
        line-height: 16px;
        margin: 8px auto;
        padding: 0px 5px 0 0px;
        text-align: right
    }

    .header h3 {
        color: #023d53;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 200px
    }

    .description h4 {
        color: #0a900a;
        font-size: 15px;
        width: 95%
    }

    .description h4 span {
        color: #c91f1f;
        display: inline-block;
        font-size: 13px;
        text-decoration: line-through;
        font-weight: 600
    }

    .btn-cart {
        border-radius: 4px !important;
        background: #b1d035;
        display: inline-block !important;
        margin-top: 10px !important;
        width: 49%;
        font-size: 12px !important;
        background: #f5f5f5
    }

    div.btn-cart {
        background: #b1d035 !important
    }

    .product-img {
        text-align: center
    }

    /* .product-img img {
 padding: 50px;
 width: 100%!important
} */

    .product-box {
        margin: .875em 0
    }

    .product-box a {
        cursor: pointer
    }

    a.Clickable-Card-Anchor {
        position: absolute;
        display: block;
        width: 100%;
        height: 86%;
        z-index: 0
    }

    .view-test-main:hover {
        background: #fff !important
    }

    .cart-page .page-header,
    .product-single-page .page-header {
        margin: 30px 0 30px;
        color: #333;
        font-size: 20px;
        font-weight: 700;
        padding: 20px 40px;
        background: #fafafa;
        border: 0;
        width: 100%
    }

    .cart-page .page-header .page-title {
        margin: 0;
        font-size: 20px
    }

    .cart-wrapper h2 {
        color: #3c3c3c;
        font-size: 24px;
        font-weight: 500;
        /* font-family: Roboto, sans-serif; */
        border-bottom: 1px solid #a8a8a8;
        padding: 6px 0 6px 0;

    }

    .cart-wrapper .left-cart {
        float: left;
        width: 70%
    }

    .cart-wrapper .right-cart {
        float: right;
        width: 30%
    }

    .cart-wrapper .left-cart p {
        color: #000;
        font-size: 18px;
        padding: 0 0 0;
        text-align: left;
        float: left;
        font-weight: 500;
        margin-bottom: 5px;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%;
    }

    .cart-wrapper .left-cart p.cart_subtitles {
        font-size: 12px;
        color: red;
    }

    .checkoutLabels {
        font-weight: bold;
        margin: 0px 15px 15px 10px;
    }

    .cart-wrapper .right-cart h3 {
        color: #3c3c3c;
        margin: 0;
        display: block;
        font-weight: 300;
        padding: 5px 0 0;
        text-align: right;
        font-size: 15px
    }

    .cart-wrapper .right-cart h4 {
        color: #08295a;
        text-align: right;
        font-size: 20px;
        font-weight: 300;
        font-family: Roboto, sans-serif;
        display: block;
        margin: 0px 0px 10px 0px;
        font-weight: bold;
    }

    .cart-wrapper .right-cart input {
        float: right;
        /* margin: 10px 0 0 0; */
        text-align: center;
        padding: 0px;
    }

    .cart-wrapper .cross {
        color: #7f7f7f;
        font-size: 15px;
        font-family: Roboto, sans-serif;
        font-weight: 300;
        margin: 0;
        position: absolute;
        left: 23px;
        bottom: 0
    }

    .cart-wrapper ul li {
        float: left;
        list-style: none;
        padding: 16px 14px 15px 0px;
        text-align: left;
        border-bottom: 1px solid #a8a8a8;
        width: 100%;
        clear: both;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        min-height: 140px;
        position: relative
    }

    .cart-wrapper ul li img {
        float: left;
        margin: -3px 6px 0 0;
        width: auto
    }

    .cart-wrapper .pre-req {
        float: left;
        width: 100%;
        text-align: left;
        font-size: 15px;
        color: #f60004;
        margin-top: 5px
    }

    .cart-wrapper {
        background: #fafafa;
        /* border-radius: 20px; */
        padding: 10px 15px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-box-shadow: 0 0 9px rgba(0, 0, 0, .27);
        -moz-box-shadow: 0 0 9px rgba(0, 0, 0, .27);
        box-shadow: 0 0 9px rgba(0, 0, 0, .27);
        background-color: white;
    }

    .login-discount {
        background: #fafafa;
        max-width: 467px;
        float: left;
        width: 100%;
        padding: 10px 20px;
        border-radius: 20px;
        margin: 0 0 25px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-box-shadow: 0 0 9px 0 rgba(0, 0, 0, .27);
        -moz-box-shadow: 0 0 9px 0 rgba(0, 0, 0, .27);
        box-shadow: 0 0 9px 0 rgba(0, 0, 0, .27)
    }

    .login-discount p {
        float: left;
        font-size: 14px;
        color: #000;
        margin: 13px 11px 10px 0
    }

    .disc-section a,
    .disc-section input[type=submit] {
        padding: 8px 10px;
        background: #00a3c8;
        color: #fff;
        font-size: 15px;
        border-radius: 50px;
        display: inline-block;
        width: 25%;
        text-align: center;
        margin: 0 0 0 4px;
        border: none;
        cursor: pointer
    }

    .cart-total-wrap {
        float: right;
        margin: 0;
        width: 100%
    }

    .cart-total {
        background: #fff;
        float: right;
        margin: 0 0 37px 0;
        width: 100%;
        /* border-radius: 20px; */
        padding: 18px;
        /* -webkit-box-shadow: 0 0 9px rgba(0, 0, 0, .27);
 -moz-box-shadow: 0 0 9px rgba(0, 0, 0, .27);
 box-shadow: 0 0 9px rgba(0, 0, 0, .27); */
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        position: relative
    }

    .cart-total-wrap input[type=submit] {
        background: #00a3c8 none repeat scroll 0 0;
        color: #fff;
        cursor: pointer;
        float: none;
        font-family: Roboto, sans-serif;
        font-size: 20px;
        margin: 15px auto 0;
        padding: 10px 0;
        text-transform: capitalize;
        clear: both;
        text-decoration: none;
        display: block;
        text-align: center;
        border: none;
        border-radius: 50px;
        max-width: 360px;
        width: 100%;
        border: 2px solid #00a3c8
    }

    .proceed-btn {
        color: #00a3c8;
        cursor: pointer;
        float: none;
        font-family: Roboto, sans-serif;
        font-size: 20px;
        margin: 15px auto 0;
        padding: 10px 0;
        text-transform: capitalize;
        clear: both;
        text-decoration: none;
        display: block;
        text-align: center;
        border: 2px solid #00a3c8;
        border-radius: 50px;
        max-width: 360px;
        width: 100%
    }

    .proceed-btn:hover {
        color: #00a3c8
    }

    .cart-total ul {
        float: left;
        width: 100%;
        padding-left: 0px;
        font-weight: 500;
    }

    .cart-total ul li {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        float: left;
        font-size: 16px;
        list-style: none;
        padding: 5px;
        width: 50%;
        text-align: right;
        color: #000;
        font-family: Roboto, sans-serif
    }

    .cart-total ul li:nth-child(odd) {
        text-align: left
    }

    .single-product-left img {
        border: 1px solid #e1e1e1
    }

    .zoom {
        display: inline-block;
        position: relative
    }

    .zoom:after {
        content: '';
        display: block;
        width: 33px;
        height: 33px;
        position: absolute;
        top: 0;
        right: 0;
        background: url(icon.png)
    }

    .zoom img {
        display: block
    }

    .zoom img::selection {
        background-color: transparent
    }

    .product-name h3 {
        text-transform: none;
        font-size: 1.875rem;
        margin-bottom: 1.25rem
    }

    .single-product-image {
        border: 1px solid #e1e1e1;
        text-align: center
    }

    .product-price h4 {
        color: #00a3c8;
        display: inline-block;
        font-size: 25px;
        font-weight: 600;
        /* margin-bottom: 1.25rem */
    }

    .product-price h4 span {
        color: red;
        padding-left: 10px;
        font-size: 23px;
        text-decoration: line-through
    }

    .product-short-desc p {
        font-size: .875rem;
        color: #929292;
        font-weight: 400;
        margin-bottom: 1.25rem
    }

    .single-product-image .product-img {
        width: 50%
    }

    .product-add-to-cart-box .quantity {
        float: left;
        margin: 0 14px 0 0
    }

    .product-add-to-cart-box .quantity input,
    .readonlyQty2 {
        height: 40px;
        border: 1px solid #e1e1e1;
        -webkit-border-radius: 0;
        -moz-border-radius: 0;
        border-radius: 0;
        width: 3.631em;
        text-align: center
    }

    .product-add-to-cart-box {
        margin-bottom: 1.25rem
    }

    .product-meta .posted_in a {
        color: red;
        font-weight: 500;
    }

    .product-meta .posted_in span,
    .product-meta .tagged_as span {
        color: #444;
        font-size: 14px;
        font-weight: 700;
        padding: 0 20px 0 0;
        text-transform: uppercase
    }

    .product-meta .posted_in a {
        color: red;
    }

    span.posted_in a:hover {
        font-weight: 400
    }

    .related_products .product-box {
        box-sizing: border-box;
        padding: 15px;
        margin: 0
    }

    .product-description {
        margin-top: 50px
    }

    .product-description #nav-tab {
        width: 40%;
        border: 0
    }

    .product-description nav {
        border-bottom: 1px solid #ccc
    }

    .product-description .tab-pane {
        box-sizing: border-box;
        padding: 10px 15px 0 15px;
        text-align: justify
    }

    .product-description .nav-tabs .nav-item.show .nav-link,
    .product-description .nav-tabs .nav-link.active {
        color: #00a3c8;
        font-weight: 700
    }

    .bredcumbs {
        text-align: center;
    }

    .bredcumbs ol {
        display: block;
    }

    .bredcumbs ol li {
        display: inline-block;
    }

    body {
        font-family: "Roboto", sans-serif !important;
    }

    li {
        font-family: "Roboto", sans-serif !important;
    }

    .home-nav-tabs {
        border-style: solid !important;
        /* padding: 20px 15px !important; */
        box-sizing: border-box;
        margin-right: 20px !important;
        font-size: 16px !important;
        font-weight: 400;
        height: 60px;
    }

    .sub_heading {
        color: #821515;
        font-weight: bold;
        font-size: 34px;
    }

    .sub_heading_desc {
        font-size: 28px;
        font-weight: 270;
        line-height: 2rem;
        text-align: justify;
    }

    .pharmacy-icon-boxes .pricing-table {
        border: 1px solid black !important;
    }

    .sub_heading_lab {
        color: #a333c8;
        font-weight: bold;
        font-size: 34px;
    }

    .sub_heading_imaging {
        color: #d17021e8;
        font-weight: bold;
        font-size: 34px;
    }

    .sub_heading_substance {
        color: #077230f6;
        font-weight: bold;
        font-size: 34px;
    }

    .sub_heading_derma {
        color: #924d15f6;
        font-weight: bold;
        font-size: 34px;
    }

    .lab_test_content .pricing-table {
        border: 1px solid black !important;
    }

    .imaging-icon-boxes {
        border: 1px solid #d17021e8 !important;
    }

    .substance-icon-boxes {
        border: 1px solid #077230f6 !important;
    }

    .derma-icon-boxes {
        border: 1px solid #924d15f6 !important;
    }

    .nav-item {
        /* padding:0px; */
        /* margin:10px 15px; */
        /* border:#a1a0a1e8 solid 3px; */
    }

    .imaging-color {
        color: #d17021e8;
    }

    .substance-color {
        color: #077230f6;
    }

    .derma-color {
        color: #924d15f6;
    }

    .box-title {
        font-size: 25px;
    }

    .green-btn {
        color: white !important;
        background-color: #077230f6 !important;
    }

    .container {
        max-width: 1360px !important;
    }


    /* Fully Page Loader */
    /* Absolute Center Spinner */
    .checkout_loader {
        position: fixed;
        z-index: 1199;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        display: none;
    }

    /* Transparent Overlay */
    .checkout_loader:before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

        background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
    }

    /* :not(:required) hides these rules from IE9 and below */
    .checkout_loader:not(:required) {
        /* hide "loading..." text */
        font: 0/0 a;
        color: transparent;
        text-shadow: none;
        background-color: transparent;
        border: 0;
    }

    .checkout_loader:not(:required):after {
        content: '';
        display: block;
        font-size: 10px;
        width: 1em;
        height: 1em;
        margin-top: -0.5em;
        -webkit-animation: spinner 150ms infinite linear;
        -moz-animation: spinner 150ms infinite linear;
        -ms-animation: spinner 150ms infinite linear;
        -o-animation: spinner 150ms infinite linear;
        animation: spinner 150ms infinite linear;
        border-radius: 0.5em;
        -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
        box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
    }

    /* Animation */

    @-webkit-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @-moz-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @-o-keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes spinner {
        0% {
            -webkit-transform: rotate(0deg);
            -moz-transform: rotate(0deg);
            -ms-transform: rotate(0deg);
            -o-transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            -webkit-transform: rotate(360deg);
            -moz-transform: rotate(360deg);
            -ms-transform: rotate(360deg);
            -o-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    /* Fully Page Loader */

    @media screen and (max-width: 640px) {
        .myLogo {
            margin-left: 1% !important;
            margin-right: 7px !important;
        }

        .ui.input>input {
            max-width: 60%;
        }

        .top-cart {
            margin-left: -83px;
            margin-right: 0px;
        }

        .shopping {
            font0-size: 22px;
        }

        .home-nav-tabs {
            font-size: 13px !important;
        }

        .sub_heading_desc {
            font-size: 1.6rem;
        }

        .desciption {
            margin-bottom: 3% !important;
        }

        #pricing-1 {
            margin-top: .5rem !important;
        }

        .h3-md {
            margin-top: 5px !important;
        }
    }

    .quantity {
        position: relative;
        float: right;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    .quantity input {
        width: 45px;
        height: 42px;
        line-height: 1.65;
        float: left;
        display: block;
        padding: 0;
        margin: 0;
        padding-left: 20px;
        border: 1px solid #eee;
    }

    .quantity input:focus {
        outline: 0;
    }

    .quantity-nav {
        float: left;
        position: relative;
        height: 42px;
    }

    .quantity-button {
        position: relative;
        cursor: pointer;
        border-left: 1px solid #eee;
        width: 20px;
        text-align: center;
        color: #333;
        font-size: 13px;
        font-family: "Trebuchet MS", Helvetica, sans-serif !important;
        line-height: 1.7;
        -webkit-transform: translateX(-100%);
        transform: translateX(-100%);
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

    .quantity-button.quantity-up {
        position: absolute;
        height: 50%;
        top: 0;
        background: #08295a;
        border: 1px solid #08295a;
        color: #fff;
    }

    .quantity-button.quantity-down {
        position: absolute;
        bottom: -1px;
        height: 50%;
        background: #08295a;
        border: 1px solid #08295a;
        color: #fff;
    }

    /* Cart Dialogue */

    #dialogueBoxCart .modal-header {
        background: #08295a;
        border: 1px solid #08295a;
    }

    .fade:not(.show) {
        opacity: 1 !important;
    }

    #dialogueBoxCart .content {
        font-weight: 500;
    }

    #dialogueBoxCart .content p {
        white-space: nowrap;
        width: 350px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 1.2rem;
    }

    #dialogueBoxCart .content span {
        white-space: nowrap;
        width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.8rem;
    }
</style>
