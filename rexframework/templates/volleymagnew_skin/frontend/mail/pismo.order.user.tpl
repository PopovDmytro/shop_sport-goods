<html>
<head>
    <style type="text/css">
        body {
            background-color: #dddddd;
            font: 12px/15px Arial, Helvetica, sans-serif;
            margin: 0px;
            padding: 0px;
        }
        body:not([id]) {
            cursor: auto !important;
        }

        .bold {
            font-weight: bold
        }

        .blue {
            background-color: #00BFFF;
            padding: 10px;
            border-radius: 5px;
            overflow: hidden;
        }

        .link {
            margin: 5px 5px 0;
        }

        .content-mail {
            width: 798px;
            margin: 0 auto;
            background-color: #FFF;
            border-right: 1px solid #A0A0A0;
            border-left: 1px solid #A0A0A0;
        }

        .blue ul {
            margin: 0px !important;
        }

        .link-text {
            float: left;
            width: 50%;
        }

        .link button {
            background-color: #ECAE1B;
            border: 1px solid #DF5A05;
            padding: 5px;
            border-radius: 5px;
        }

        .link a {
            cursor: pointer;
            text-decoration: none;
        }

        .conteni-text-mail {
            padding: 0px 15px 5px;
        }

        .contact-mail {
            width: 60%;
        }

        .contact-mail li {
            margin-bottom: 5px;
        }

        .conteni-text-mail hr {
            border-color: #dddddd;
        }

        /*new*/
        .order-b {
            border: 2px solid #098AC9;
            border-radius: 5px;
            margin: 25px auto;
            padding: 5px;
        }
        .order-list-table-id {
            background-color: #E6F7FD;
            font-size: 16px;
            padding: 10px;
        }
        .order-list-table-date {
            padding-top: 16px;
            padding-left: 10px;
        }
        span.hr_dot {
            border-bottom: 2px dotted #03A5D6;
            width: 100%;
            display: block;
            margin: 5px 0;
        }
        .order-list-table-down-img {
            width: 70px;
        }
        a {
            text-decoration: none;
            color: #00f;
        }
        img {
            border-style: none;
        }
        .name-product {
            width: 21%;
            padding: 0 20px;
        }
        .cart-title {
            margin-top: 10px;
        }
        .cart-title a {
            color: #287FCB;
            display: inline-block;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: normal;
            line-height: 16px;
        }
        .prod-count {
            width: 14%;
        }
        .order-list-table-down-count {
            border-right: 2px dotted #03A5D6;
            border-left: 2px dotted #03A5D6;
            vertical-align: middle;
            width: 35%;
        }
        .order-list-table-down-count .cart-attr td:first-child {
            width: 36%;
        }
        .order-list-table .cart-attr td:first-child {
        }
        .order-list-table .cart-attr td, .order-list-table .cart-price {
            padding: 5px;
        }
        .cart-attr-l {
            text-align: right;
        }
        .cart-article {
            padding-left: 24px;
        }
        .prod-price {
            text-align: center;
        }
        .price-not-sale {
            text-decoration: line-through;
            color: #C3C3C3;
        }
        .order-not-sale {
            color: #C3C3C3;
            text-decoration: line-through;
            font-weight: normal;
            font-size: 13px;
        }
        .order-list-table-com {
            display: inline-block;
            padding: 4px;
            position: relative;
            width: 400px;
        }
        #order-price {
            color: #008000;
            display: inline-block;
            font-size: 14px;
            font-style: italic;
            font-weight: bold;
            margin: 10px 0 0;
            width: 300px;
        }
        /*new*/
    </style>
</head>
<body>
<div class="content-mail">
    {*<a style="width:100%; display:block" href="http://www.volleymag.com.ua"><img style="margin:0 auto; display: block;" src="http://www.volleymag.com.ua/content/mail/logo.png" /></a>*}
    <div class="conteni-text-mail" style="font: 12px/15px Arial, Helvetica, sans-serif">
        <header style="font-style: italic; padding-top: 10px;">
            <p><b>Здравствуйте!</b></p>
            <p><b>Вы оформили заказ в онлайн-магазине VolleyMAG!</b></p>
        </header>
        {if $pismo}
            {$pismo = $pismo}
        {else}
            {$pismo = false}
        {/if}
        {include file="order/orders.list.div.tpl" order = $order pismo = $pismo}
        <footer style="font-style: italic">
            <p><b>В ближайшее время с Вами свяжется менеджер заказа!</b></p>
            <p><b>Хорошего Вам дня!</b></p>
            --<br>
            <div style="color: #808080">
                С уважением,<br>
                коллектив онлайн-магазина<br>
                VolleyMAG<br>
                099-923-81-89<br>
                097-948-50-39<br>
                zakaz@volleymag.com.ua<br>
            </div>
            <br>
            <img width="150" src="http://www.volleymag.com.ua/skin/volleymag_skin/frontend/img/mail-logo.png" itemprop="logo">
        </footer>
    </div>
    {*<div style="width:98%; overflow:hidden; padding:10px 1%; background-color: #F1F1F1;">
        <a style="float:right; display:block" href="http://www.volleymag.com.ua"><img src="http://www.volleymag.com.ua/content/mail/logo.png" /></a>
    </div>*}

</div>
</body>
</html>