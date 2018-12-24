<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Корзина товаров</a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="column small-12"><h1 class="section-title section-title--blue">Корзина товаров</h1></div>
</div>

<div id="cart">
    <div class="product-def">
        <div class="product-def-top-bg"></div>
        {page type='getRenderedMessages'}
        {page type='getRenderedErrors'}

        <div class="into-box info-block">
            {if $cartList}
                <div id="cart_items">
                    <form action="" method="post" id="cartForm">
                    <input type="hidden" name="mod" value="order" id="mod">
                    <input type="hidden" name="act" value="add" id="act">
                        {assign var=nitem value=1}
                        <div id="cart-main-table" class="checkout_table-row">
                            <div class="checkout_thead-row">
                                <div class="row collapse">
                                    <div class="checkout_th columns small-1"><h3>Товар</h3></div>
                                    <div class="checkout_th columns small-2 small-offset-1"><h3>Название</h3></div>
                                    <div class="checkout_th columns small-2"><h3>Параметры</h3></div>
                                    {*{if $issetUncheckedSku}<div class="checkout_th columns small-1 small-offset-1"><h3>Размер</h3></div>{/if}*}
                                    <div class="checkout_th columns small-2 "><h3>Количество</h3></div>
                                    <div class="checkout_th columns small-1 small-offset-1"><h3>Цена</h3></div>
                                    <div class="checkout_th columns small-1 small-offset-1"><h3>Удалить</h3></div>
                                </div>
                            </div>
                            {foreach from=$cartList key=key item=cart name=cartitem}
                                {assign var=cartEntity value=$cart.cart}
                                {if $cart.product}
                                     <div class="checkout_tbody-row">
                                         <div class="row collapse">
                                             {assign var=productEntity value=$cart.product}
                                             {assign var=image value=$cart.image}
                                             {assign var=pcatalog_alias value=$cart.pcatalog_alias}
                                             {*<div class="cart-li" {if !$smarty.foreach.cartitem.last}style="border-bottom:2px dotted #03A5D6;"{/if}>*}
                                             <input type="hidden" name="cart[{$nitem}][product_id]"
                                                    value="{$productEntity->id}">
                                             <input type="hidden" name="cart[{$nitem}][attributes]"
                                                    value="{$cartEntity->attributes}">
                                             {if $cartEntity->sku}
                                                 <input type="hidden" name="cart[{$nitem}][sku]" value="{$cartEntity->sku}">
                                             {/if}
                                             <div class="checkout_td columns small-1">
                                                 {*<a href="{url mod=product act=default cat_alias=$pcatalog_alias task=$productEntity->id}">*}
                                                 <div class="pic-holder">
                                                 {if $image}
                                                     <a style="position:relative; display:block;" id="imgfull"
                                                        class="gallery rex-tooltip-disable"
                                                        title="{if $productBrand}{$productBrand->name} {/if}{$product->name}"
                                                        href="{getimg type=main name=pImage id=$image.id ext=$image.image}">
                                                         <img id="imageFull" src="{getimg type=icon name=pImage id=$image.id ext=$image.image}"/>
                                                     </a>
                                                 {else}
                                                     {img src="default-icon-120.jpg" class="t-image"}
                                                 {/if}
                                                 {*</a>*}
                                                 {literal}
                                                 </div>
                                                 <script> prev_id = '{/literal}{$image.id}{literal}' </script>{/literal}
                                             </div>
                                             <div class="checkout_td columns small-2 small-offset-1">
                                                 <a class="product-name" href="{url mod=product act=default cat_alias=$pcatalog_alias task=$productEntity->id}">
                                                     {$productEntity->name}
                                                 </a>
                                             </div>
{*TODO check sku text *}
                                             <div class="checkout_td columns small-2">
                                                 <div class="">
                                                     <div>
                                                         <span class="cart-attr-l">Артикул:</span>
                                                         <span class="cart-attr-r">
                                                             {if $cart.skuEntity}
                                                                 {$cart.skuEntity->sku_article}
                                                             {elseif $cart.skuByColor}
                                                                 {$cart.skuByColor.sku_article}
                                                                 <input type="hidden" name="cart[{$nitem}][sku_color]"
                                                                        value="{$cart.skuByColor.id}">
                                                             {else}
                                                                 {$productEntity->id}
                                                             {/if}
                                                         </span>
                                                     </div>
                                                     {if $cart.attributes}
                                                         {foreach from=$cart.attributes key=attributeKey item=attributeValue}
                                                             {assign var=attr_key value=$attributeValue.key}
                                                             {assign var=attr_value value=$attributeValue.value}
                                                             {if $attr_value->name}
                                                                 <div>
                                                                     <span class="cart-attr-l">{$attr_key->name}</span>
                                                                     <span class="cart-attr-r">{$attr_value->name}</span>
                                                                 </div>
                                                             {/if}
                                                         {/foreach}
                                                     {/if}
                                                     {if $cart.sku}
                                                         <div>
                                                             {$cart.sku}
                                                         </div>
                                                     {/if}
                                                     {if $issetUncheckedSku}
                                                         <div class="size-select {if !$cart.atributeListByColor}fl-none{/if}">
                                                             {if $cart.atributeListByColor}
                                                                 <select name="cart[{$nitem}][sku]" class="sku-by-color">
                                                                     <option value="1">Не выбран</option>
                                                                     {foreach from=$cart.atributeListByColor item=atributeByColor}
                                                                         {if $atributeByColor.name neq 'Пол'}
                                                                             <option value="{$atributeByColor.sku_id}">{$atributeByColor.value}</option>
                                                                         {/if}
                                                                     {/foreach}
                                                                 </select>
                                                             {/if}
                                                         </div>
                                                     {/if}
                                                 </div>
                                             </div>
{**}
                                             <div class="checkout_td columns small-2">
                                                 <div class="checkout_stock">
                                                 {*if $smarty.server.REMOTE_ADDR == '37.57.56.101'*}
                                                     <button type="button" data-id="{$nitem}" class="checkout_btn checkout_btn--decrease count-minus back"></button>
                                                     <input item-id="{$nitem}" class="cart-amount" type="text"
                                                            name="cart[{$nitem}][count]" value="{$cartEntity->count}"
                                                            id="value-cart-{$nitem}">
                                                     <button type="button" data-id="{$nitem}" class="checkout_btn checkout_btn--increase count-plus forward"></button>
                                                 {*else}
                                                     <input item-id="{$productEntity->id}{if $cartEntity->sku}-{$cartEntity->sku}{/if}" class="cart-amount" type="text" name="cart[{$nitem}][count]" value="{$cartEntity->count}">
                                                 {/if*}
                                                 </div>
                                             </div>
                                             <div class="checkout_td columns small-1 small-offset-1 align-center">
                                                 <div class="cart-price">
                                                     <span class="price_ua"><span>{$productEntity->price|floor}</span> грн</span>
                                                     <span item-id="{$nitem}" class="cart-prices" id="prod-price-{$nitem}"
                                                           style="display: none;">{$productEntity->price|floor}</span>
                                                 </div>
                                             </div>
                                             <div class="checkout_td columns small-1 small-offset-1 align-center">
                                                 {assign var=fddd value=$fddd + 1}
                                                 <a class="checkout_btn checkout_btn--remove" id="free_button" href="{url mod=cart act=clear id=$cartEntity->num}">{img src="close-popup.png" alt="close icon"}</a>
                                             </div>
                                         </div>
                                    </div>
                                    {assign var=nitem value=$nitem + 1}
                                    {if !$smarty.foreach.cartitem.last}
                                        {*<hr />*}
                                    {/if}
                                {/if}
                            {/foreach}
                            {*if $user}
                                {if $order_count >= 1}
                                    {if $order_summa > 500000}
                                        <div class="sale_cart">Ваша скидка: 10%</div>
                                    {elseif $order_summa > 200000}
                                         <div class="sale_cart">Ваша скидка: 5%</div>
                                    {elseif $order_summa > 100000}
                                        <div class="sale_cart">Ваша скидка: 3%</div>
                                    {else}
                                        <div class="sale_cart">Ваша скидка: 2%</div>
                                    {/if}
                                {else}
                                    {if $order_summa > 500000}
                                        <div class="sale_cart">Ваша скидка: 10%</div>
                                    {elseif $order_summa > 200000}
                                         <div class="sale_cart">Ваша скидка: 5%</div>
                                    {elseif $order_summa > 100000}
                                        <div class="sale_cart">Ваша скидка: 3%</div>
                                    {/if}
                                {/if}
                            {/if*}
                            <div class="checkout_total-amount">
                                <div class="row collapse">
                                    <div class="columns">
                                        <dl class="amount-dl full-price">
                                            <dt>Всего к оплате:</dt>
                                            <dd><span class="cart-alltogether"></span>&nbsp;<span>грн</span></dd>
                                        </dl>
                                        <dl >(со скидкой {$sale}%)</dl>
                                    </div>
                                </div>
                            </div>
                            <div class="checkout_btns-row">
                                <div class="row collapse">
                                    <div class="btn-holder columns shrink">
                                        <button type="button" class="btn btn-blue " id="free_button" onclick="history.back()" ><i aria-hidden="true" class="fa fa-angle-left"></i>Обратно к покупкам</button>
                                    </div>
                                    <div class="btn-holder columns shrink">
                                        <button type="button" class="btn btn-blue " id="free_button" onclick="document.location.href='{url mod=cart act=clear}'" >Очистить корзину</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div  id="cart-orders" class="checkout_form" >
                            <div class="row collapse">
                                <div class="columns small-12">
                                    <ul class="checkout_payment-methods no-bullet">
                                        <li>
                                            <div class="radio-holder top-trigger">
                                                <label>
                                                    <input type="radio" {if $odelivery == 'Полная предоплата' or not $odelivery}checked{/if} name="order[delivery]" value="Полная предоплата">
                                                    <span class="checkbox-trigger checkbox-trigger--grey"></span>
                                                    <span class="lbl">
                                                        Полная предоплата (переводом на карту или через терминал Приватбанка)
                                                        {*Полная предоплата (перевод на банковскую карту)*}
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="radio-holder top-trigger">
                                                <label>
                                                    <input type="radio" {if $odelivery == 'Наложенный платеж'}checked{/if} name="order[delivery]" value="Наложенный платеж">
                                                    <span class="checkbox-trigger checkbox-trigger--grey"></span>
                                                    <span class="lbl">Наложенный платеж (оплата заказа после его фактического получения на складе транспортной компании)<br><i>Внимание!<br>
                                                        Наложеным платежом отправляются заказы на общую сумму более 400 грн.</i>
                                                        {*Наложенный платеж (оплата заказа после его фактического получения на складе транспортной компании)*}
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="radio-holder top-trigger">
                                                <label>
                                                    <input type="radio" {if $odelivery == 'Безналичный расчет'}checked{/if} name="order[delivery]" value="Безналичный расчет">
                                                    <span class="checkbox-trigger checkbox-trigger--grey"></span>
                                                    <span class="lbl">
                                                        {*Безналичный расчет*}
                                                        Безналичный расчет (для юридических лиц)
                                                    </span>
                                                </label>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="checkout_form-wrapper row">
                                        <div class="columns large-5 row">
                                            <div class="row small-up-1">
                                                {if !$user}
                                                <div class="input-holder column">
                                                    <input placeholder="Имя" class="titlex" type="text" name="order[lastname]" value="" id="lastname">
                                                </div>
                                                <div class="input-holder column">
                                                    <input placeholder="Фамилия" class="titlex" type="text" name="order[name]" value="" id="name">
                                                </div>
                                                <div class="input-holder column">
                                                    <input class="titlex" type="text" name="order[phone]" value="" id="phone" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                                                </div>
                                                <div class="input-holder column">
                                                    <input placeholder="Email (example@example.com)" class="titlex" type="text" name="order[email]" value="" id="email">
                                                </div>
                                                <div class="input-holder column">
                                                    <input placeholder="Введите ваш город" id="searchcity" class="search titlex" name="order[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Населенный пункт доставки';}" onfocus="javascript: if (this.value=='' || this.value=='Населенный пункт доставки') {this.value='';}{/literal}" value="{if $q}{$q}{else}Населенный пункт доставки{/if}" />
                                                </div>
                                                {else}
                                                <div class="input-holder column">
                                                    <input placeholder="Имя" class="titlex" name="order[name]" value="{$user.name}">
                                                </div>
                                                <div class="input-holder column">
                                                    <input placeholder="Фамилия" class="titlex" name="order[lastname]" value="{$user.lastname}">
                                                </div>
                                                <div class="input-holder column">
                                                    <input class="titlex" name="order[phone]" value="{if $user.phone}{$user.phone}{/if}" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                                                </div>
                                                <div class="input-holder column">
                                                    <input placeholder="Email (example@example.com)" class="titlex" name="order[email]" value="{if $user.email}{$user.email}{/if}">
                                                </div>
                                                <div class="input-holder column">
                                                    <input placeholder="Населенный пункт доставки" id="searchcity" class="search titlex" name="order[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Населенный пункт доставки';}" onfocus="javascript: if (this.value=='' || this.value=='Населенный пункт доставки') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$user.city}{/if}" />
                                                </div>
                                                {/if}
                                                <input type="hidden" id="city-val" name="" value="{$usercity_id}">
                                                {*{foreach from=$city item=icity}{if $user.city eq $icity.id}{$icity.name}{/if}{/foreach}*}
                                                <div class="input-holder column">
                                                    <textarea placeholder="Комментарий к заказу" class="cart-textarea" name="order[comment]">{if $ocomment}{$ocomment}{/if}</textarea>
                                                </div>
                                                <div class="input-holder column">
                                                    <div class="radio-holder top-trigger">
                                                        <label>
                                                            <input type="checkbox" {if $oconfirm}checked{/if} id="do_not_call_to_confirm" name="order[confirm]" title="Не звонить для подтверждения заказа">
                                                            <span class="checkbox-trigger checkbox-trigger--grey"></span>
                                                            <span class="lbl">Не звонить для подтверждения заказа</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="input-holder column">
                                                    {*<input type="submit" class="cart-create free_button {if $issetUncheckedSku}cart-create--inactive{/if}" name="cart[submit]" value="Оформить заказ" id="order-submit-button"></td>*}
                                                    <input type="submit" class="btn btn--blue cart-create free_button" name="cart[submit]" value="Оформить заказ" id="order-submit-button">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {*<a href="#x" class="overlay" id="amply"></a>*}
                {*<div class="benefits">
                    <div >
                        {include file='product/benefits.tpl'}
                    </div>
                    <a class="close"title="Закрыть" href="#close"></a>
                </div>*}
            {else}
                <div class="page_text">
                    <h2 class="text-center">Ваша корзина пуста!</h2>
                </div>
            {/if}
        </div>
    </div>
    {include file="_block/input.phone.mask.tpl"}
<script language="javascript">
{rexscript_start}
var discount = {$sale};
{literal}
function floorN(x, n) {
     //var mult = Math.pow(10, n);
     //return Math.floor(x*mult)/mult;
     return Math.floor(x);
}

$(document).ready(function(){

        $('.count-minus').die('click').live('click', function(){
            var alltogether = 0;
            var id = $(this).attr('data-id');
            var price = $('#prod-price-'+id).text();
            var c =  $('#value-cart-'+id).val();
            c--;

            var salecount = $('#value-cart-'+id).val();
            if(c <= 1) {
                //$('.count-minus').addClass('count-minus-disabled');
                c = 1;
            }

            alltogether += parseInt(floorN(price)) * c;
            $('.cart-prices').each(function(){
                if (price != $(this).text()) {
                    var idnew = $(this).attr('item-id');
                    var cnew = $('#value-cart-'+idnew).val();
                    alltogether += (parseInt($(this).text()) * cnew);
                }
            });
            $('#value-cart-'+id).val(c);
            $('.cart-alltogether').text(floorN(alltogether - alltogether*discount/100, 2));
        });
        $('.count-plus').die('click').live('click', function(){
            var id = $(this).attr('data-id');
            var alltogether = 0;
            var price = $('#prod-price-'+id).text();
            var c =  $('#value-cart-'+id).val();
            c++;
            /*if (c > 1) {
                $('.count-minus').removeClass('count-minus-disabled');
            }*/
            alltogether += parseInt(price) * c;
            $('.cart-prices').each(function(){
                if (price != $(this).text()) {
                    var idnew = $(this).attr('item-id');
                    var cnew = $('#value-cart-'+idnew).val();

                    alltogether += (parseInt($(this).text()) * cnew);
                }
            });
            $('#value-cart-'+id).val(c);

            $('.cart-alltogether').text(floorN(alltogether - alltogether*discount/100, 2));
        });
        initPhoneMask();
    });

    $('.cart-amount').unbind('keyup').bind('keyup', function(event){
        var alltogether = 0;
        $('.cart-amount').each(function(){
            var id = $(this).attr('item-id');
            var price = $('.cart-prices[item-id=' + id + ']').text();
            var count = $(this).val();
            if (!count || count < 0 || isNaN(count)){
                count = 1;
            }
            //console.log($('.cart-price[item-id=' + id + ']').text());
            alltogether += count * price;
        });
        $('.cart-alltogether').text(floorN(alltogether - alltogether*discount/100, 2));
    });
    $('.cart-amount').keyup();




    $('#order-submit-button').die('click').live('click', function(){
        if ($(this).hasClass('cart-create--inactive')) {
            alert('Выберите все свойства товаров - их размеры и цвета');
            return false;
        }
        var valid = /^[a-zа-яё]{2,}$/i;
        var email = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        //($('#lastname').length && $('#lastname').val().length < 2) || 
        if (!$('#lastname').val().match(valid)) {
            alert('Некорректная фамилия пользователя');
            return false;
        } else if (!$('#name').val().match(valid)) {
            alert('Некорректное имя пользователя');
            return false;
        }/* else if ($('#email').length > 0) {
            if (!$('#email').val().match(email)) {
                alert('Некорректный email пользователя');
                return false;
            }
        }*/ else if ($('#phone').length && ($('#phone').val().length !== 12 || !$.isNumeric($('#phone').val()))) {
            alert('Ошибка в идентификаторе пользователя, необходимо указать хотя бы телефон(в формате 380ХХХХХХХХХ). Номер должен состоять из 12 цифр');
            return false;
        } else {
            $('#cartForm').append('<input type="hidden" name="cart[submit]" value="1">').submit();
            return false;
        }
    });
    
    // window.selectClone = $('select[name="order[fillials]"]').clone();

    //     var checkSelect = function(featured)
    //     {
    //         $this = window.selectClone.clone();
    //         //var featured2 = $('#select option:selected').attr('cid');
    //         if (featured != 0 && $this.find('option[cid="'+featured+'"]').length > 0) {
    //             $this.find('option[cid!="'+featured+'"]').remove();
    //         } else if (featured != 0 && !$this.find('option[cid="'+featured+'"]').length) {
    //             $this.val(0).find('option[value!="0"]').remove();
    //         } else {
    //             $this.val(featured);
    //         }

    //         $('select[name="order[fillials]"]').replaceWith($this);
    //     };

    //     if ($('#city-val').val()) {
    //         checkSelect($('#city-val').val());
    //     }

    function load_fillials(cid) {
        var fillials = $.rex('user', 'FillialsByCityId', {task: cid, template: 'cart' });
            if (fillials != 'false') {
                $('.fillials_rt').replaceWith(fillials);
            }  else {
                $('.fillials_rt').html('');
            }
    }

    if ($('#city-val').val()) {
        load_fillials($('#city-val').val());
    }

    $('#city-val').on('change', function(){
         load_fillials($('#city-val').val());
    });

    $("#searchcity").autocomplete("/autocompletecart/", {
        selectFirst: false,
        minLength: 2,
        width: 420,
        scrollHeight: 400,
        max: 30,
        formatItem: function(data, i, n, value) {
            city = value.split('=')[0];
            city_id = value.split('=')[1];
            //return '<option value="'+id+'">'+city+'</option>';
            return '<div class="city" cid="'+ city_id +'" value="'+ city +'">'+ city +'</div>'

        }, formatResult: function(data, i, n) {
                return i.split('=')[0];
        }
    }).result(function(event, item) {
        var id = item[0].split('=')[1];
        $('#city-val').val(id).trigger('change');
        return false;
    });
    
    $("#searchcity").on('change', function(){
        $(this).next().val(0).trigger('change');
    });

    function loadImg (id, ext){
            prev_id = id;
            document.getElementById("imageFull").setAttribute("src",  "/content/images/pimage/"+id+"/icon."+ext);
            document.getElementById("imgfull").setAttribute("href", "/content/images/pimage/"+id+"/main."+ext);
            //$('.a_gallery').lightBox();
            generateLightbox();
        }
{/literal}
{rexscript_stop}
</script>
</div>