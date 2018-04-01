<ul class="breadcrumbs">
        <li><a href="{url mod=home}">Главная</a></li>
        <li>Корзина товаров</li>
</ul>
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
                        <table cellpadding="2" cellspacing="0" border="0"  width="100%" id="cart-main-table">
                            <tr class="head-tr">
                                <th>Изображение</th>
                                <th>Название</th>
                                <th>Параметры</th>
                                {if $issetUncheckedSku}<th>Размер</th>{/if}
                                <th>Количество</th>
                                <th>Стоимость</th>
                                <th></th>
                            </tr>
                            {foreach from=$cartList key=key item=cart name=cartitem}
                                {assign var=cartEntity value=$cart.cart}
                                {if $cart.product}
                                     <tr class="cart-li">
                                    {assign var=productEntity value=$cart.product}
                                    {assign var=image value=$cart.image}
                                    {assign var=pcatalog_alias value=$cart.pcatalog_alias}
                                    {*<div class="cart-li" {if !$smarty.foreach.cartitem.last}style="border-bottom:2px dotted #03A5D6;"{/if}>*}
                                        <input type="hidden" name="cart[{$nitem}][product_id]"     value="{$productEntity->id}">
                                        <input type="hidden" name="cart[{$nitem}][attributes]"     value="{$cartEntity->attributes}">
                                        {if $cartEntity->sku}<input type="hidden" name="cart[{$nitem}][sku]" value="{$cartEntity->sku}">{/if}

                                        <td valign="top" width="130px;" class="cart-img">
                                            {*<a href="{url mod=product act=default cat_alias=$pcatalog_alias task=$productEntity->id}">*}
                                                {if $image}
                                                <a style="position:relative; display:block;" id="imgfull" class="gallery rex-tooltip-disable" title="{if $productBrand}{$productBrand->name} {/if}{$product->name}" href="{getimg type=main name=pImage id=$image.id ext=$image.image}">
                                                    <img  id="imageFull" src="{getimg type=icon name=pImage id=$image.id ext=$image.image}" />
                                                </a>
                                                {else}
                                                      {img src="default-icon-120.jpg" class="t-image"}
                                                {/if}
                                            {*</a>*}
                                            {literal}<script> prev_id = '{/literal}{$image.id}{literal}' </script>{/literal}
                                        </td>
                                        <td valign="middle" width="190" class="product-title">
                                            <div class="cart-title">
                                                <a href="{url mod=product act=default cat_alias=$pcatalog_alias task=$productEntity->id}">
                                                    {$productEntity->name}
                                                </a>
                                            </div>
                                        </td>
                                        <td width="18%" class="attr-cart">
                                            <table cellpadding="0" cellspacing="0" border="0" class="cart-attr">
                                                <tr>
                                                    <td class="cart-attr-l">Артикул:</td>
                                                    <td class="cart-attr-r">
                                                        {if $cart.skuEntity}
                                                            {$cart.skuEntity->sku_article}
                                                        {elseif $cart.skuByColor}
                                                            {$cart.skuByColor.sku_article}
                                                            <input type="hidden" name="cart[{$nitem}][sku_color]" value="{$cart.skuByColor.id}">
                                                        {else}
                                                            {$productEntity->id}
                                                        {/if}
                                                    </td>
                                                </tr>
                                                {if $cart.attributes}
                                                    {foreach from=$cart.attributes key=attributeKey item=attributeValue}
                                                        {assign var=attr_key value=$attributeValue.key}
                                                        {assign var=attr_value value=$attributeValue.value}
                                                        {if $attr_value->name}
                                                        <tr>
                                                            <td class="cart-attr-l">{$attr_key->name}</td>
                                                            <td class="cart-attr-r">{$attr_value->name}</td>
                                                        </tr>
                                                        {/if}
                                                    {/foreach}
                                                {/if}
                                                {if $cart.sku}
                                                    <tr>
                                                        {$cart.sku}
                                                    </tr>
                                                {/if}
                                            </table>
                                        </td>
                                        {if $issetUncheckedSku}
                                            <td class="size-select {if !$cart.atributeListByColor}fl-none{/if}">
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
                                            </td>
                                        {/if}
                                        <td class="cart-attr-r count {if !$cart.atributeListByColor}fl-none{/if}" align="center">
                                        {*if $smarty.server.REMOTE_ADDR == '37.57.56.101'*}
                                        <div data-id="{$nitem}" class="count-minus back"></div>
                                            <input item-id="{$nitem}" class="cart-amount" type="text" name="cart[{$nitem}][count]" value="{$cartEntity->count}" id="value-cart-{$nitem}">
                                        <div data-id="{$nitem}" class="count-plus forward"></div>
                                        {*else}
                                            <input item-id="{$productEntity->id}{if $cartEntity->sku}-{$cartEntity->sku}{/if}" class="cart-amount" type="text" name="cart[{$nitem}][count]" value="{$cartEntity->count}">
                                        {/if*}

                                        </td>
                                        <td width="90px;" align="center" class="prod-price">
                                            <div class="cart-price"><span class="price_ua"><span>{$productEntity->price|floor}</span> грн</span>
                                            <span item-id="{$nitem}" class="cart-prices" id="prod-price-{$nitem}" style="display: none;">{$productEntity->price|floor}</span>
                                            </div>
                                        </td>
                                        <td class="button-td">
                                        {assign var=fddd value=$fddd + 1}
                                            <a id="free_button" href="{url mod=cart act=clear id=$cartEntity->num}">Удалить</a>

                                        </td>
                                        <td class="clear"></td>
                                    </tr>
                                    {assign var=nitem value=$nitem + 1}
                                    {if !$smarty.foreach.cartitem.last}
                                        <tr>
                                            <td colspan="6">
                                                <span class="hr_dot"></span>
                                            </td>
                                        </tr>
                                    {/if}
                                {/if}
                            {/foreach}
                        </table>
                    <span class="hr"></span>
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
                    <input type="button" class="cart-clear" id="free_button" onclick="history.back()" value="Вернуться к покупкам">
                    <input type="button" class="cart-clear" id="free_button" onclick="document.location.href='{url mod=cart act=clear}'" value="Очистить корзину">
                    <span style="float:right;margin-top:5px;" class="full-price"><b>Всего: <span class="cart-alltogether"></span> грн</b><br><span style="font-size:10px; color:red; margin-top:10px">(со скидкой {$sale}%)</span></span>
                    <div class="clear"> </div>
                    <table cellpadding="0" cellspacing="0" id="cart-orders">
                        {if !$user}
                            <tr class="user_info">
                                <td class="cart-attr-l">
                                    <p class="cart-prim">Фамилия :</p>
                                    <p class="cart-prim">Имя :</p>
                                </td>
                            </tr>
                            <tr class="user_info">
                                <td class="cart-attr-l" class="2">
                                    <input class="titlex" type="text" name="order[lastname]" value="" id="lastname">
                                    <input class="titlex" type="text" name="order[name]" value="" id="name">
                                </td>
                            </tr>
                            
                            <tr>
                                <td class="cart-attr-l" colspan="2"><p class="cart-prim">Мобильный телефон (380991112233):</p></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l" colspan="2"><input class="titlex" type="text" name="order[phone]" value="" id="phone" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l" colspan="2"><p class="cart-prim">Email (example@example.com):</p></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l" colspan="2"><input class="titlex" type="text" name="order[email]" value="" id="email"></td>
                            </tr>

                        {/if}
                            <tr>
                                <td class="cart-attr-l" colspan="2">
                                    {*<select name="order[delivery]" style="width: 250px" class="select_default titlex">
                                        <option value="Выберите способ оплаты и доставки" >Выберите способ оплаты и доставки</option>
                                        <option value="Полная предоплата">Полная предоплата</option>
                                        <option value="Наложенный платеж">Наложенный платеж</option>
                                        <option value="Оплата наличными курьеру">Оплата наличными курьеру</option>
                                    </select>*}
                                    <table>
                                        <tr style="height: 50px;">
                                            <td><input type="radio" {if $odelivery == 'Полная предоплата' or not $odelivery}checked{/if} name="order[delivery]" value="Полная предоплата"></td>
                                            <td>Полная предоплата (перевод на банковскую карту)</td>
                                        </tr>
                                        <tr style="height: 50px;">
                                            <td><input type="radio" {if $odelivery == 'Наложенный платеж'}checked{/if}name="order[delivery]" value="Наложенный платеж"></td>
                                            <td>Наложенный платеж (оплата заказа после его фактического получения на складе транспортной компании)</td>
                                        </tr>
                                        <tr style="height: 50px;">
                                            <td><input type="radio" {if $odelivery == 'Безналичный расчет'}checked{/if}name="order[delivery]" value="Безналичный расчет"></td>
                                            <td>Безналичный расчет (для юридических лиц)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="attention" colspan="2">  Внимание! Наложеным платежом отправляются заказы на общую сумму более 400 грн.</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>


                            {*if !$user*}
                            <tr>
                                <td class="cart-attr-l">
                                {if !$user}
                                    <div><b>Населенный пункт доставки:</b></div>
                                    <input id="searchcity" class="search titlex" name="order[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Населенный пункт доставки';}" onfocus="javascript: if (this.value=='' || this.value=='Населенный пункт доставки') {this.value='';}{/literal}" value="{if $q}{$q}{else}Населенный пункт доставки{/if}" />
                                {else}
                                    <div><b>Имя:</b></div>
                                    <input class="titlex" name="order[name]" value="{$user.name}">
                                    <div><b>Фамилия:</b></div>
                                    <input class="titlex" name="order[lastname]" value="{$user.lastname}">
                                    <div><b>Мобильный телефон (+380991112233):</b></div>
                                    <input class="titlex" name="order[phone]" value="{if $user.phone}{$user.phone}{/if}" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__">
                                    <div><b>Email (example@example.com):</b></div>
                                    <input class="titlex" name="order[email]" value="{if $user.email}{$user.email}{/if}">
                                    <div><b>Населенный пункт доставки:</b></div>
                                    <input id="searchcity" class="search titlex" name="order[city]" onblur="{literal}javascript: if (this.value=='') {this.value='Населенный пункт доставки';}" onfocus="javascript: if (this.value=='' || this.value=='Населенный пункт доставки') {this.value='';}{/literal}" value="{if $q}{$q}{else}{$user.city}{/if}" />
                                {/if}
                                    <input type="hidden" id="city-val" name="" value="{$usercity_id}">
                                    {*{foreach from=$city item=icity}{if $user.city eq $icity.id}{$icity.name}{/if}{/foreach}*}
                                </td>
                            </tr>
                            <tr class="fillials_rt"></tr>
                            {*<tr class="fillials_rt">
                                <td class="cart-attr-l">
                                <div><b>Адрес отделения транспортной компании:</b></div>
                                    <select name="order[fillials]" style="width: 260px" class="select_default titlex">
                                        <option id="Selcity" value="0">Адрес отделения транспортной компании</option>
                                        {foreach from=$fillials item=ifillials}
                                            <option value="{$ifillials.id}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.id}selected{/if}>{$ifillials.name}</option>
                                        {/foreach}
                                    </select>
                                </td>
                            </tr>*}
                            {*/if*}
                            <tr>
                                <td class="cart-attr-l" colspan="2">
                                    <input type="checkbox" {if $oconfirm}checked{/if} id="do_not_call_to_confirm" name="order[confirm]" title="Не звонить для подтверждения заказа">
                                    <b>
                                        Не звонить для подтверждения заказа
                                    </b>
                                </td>
                            </tr>
                             <tr>
                                <td class="cart-attr-l" colspan="2"><p class="cart-prim">Примечание:</p></td>
                            </tr>
                            <tr class="block-td">
                                <td class="cart-attr-l" colspan="2"><textarea class="cart-textarea" name="order[comment]">{if $ocomment}{$ocomment}{/if}</textarea></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l-create" colspan="2" align="left">
                                {*<input type="submit" class="cart-create free_button {if $issetUncheckedSku}cart-create--inactive{/if}" name="cart[submit]" value="Оформить заказ" id="order-submit-button"></td>*}
                                <input type="submit" class="cart-create free_button" name="cart[submit]" value="Оформить заказ" id="order-submit-button"></td>
                            </tr>
                        </table>

                        <div class="text-container">
                            {*<div class="delivery">
                                <span class="heading">Доставка и оплата</span>
                                <ul>
                                    <li>по Харькову: 35 грн. -  бесплатно</li>
                                    <li>в регионы: 35 грн.</li>
                                    <li>адресная доставка в регионы: 35 грн.</li>
                                    <li>оплата при получении товара</li>
                                </ul>
                                <span class="warranty">Гарантия 12 месяцев</span>
                                <span>обмен/возврат товара в течение 14 дней</span>
                                <a class="more" href="{url mod=staticPage task='delivery'}">Подробнее</a>
                            </div>*}
                            <div class="delivery">
                                <div class="heading">Наши преимущества:</div>
                                <ul>
                                    <li class="deliver">Доставка по всей Украине</li>
                                    <li class="checked">Актуальные размеры</li>
                                    <li class="assurance">Гарантия подлинности</li>
                                    <li class="return">Гарантия возврата</li>
                                    <li class="sale">Дополнительные скидки для постоянных покупателей</li>
                                </ul>
                                <a class="more" href="#amply">Подробнее</a>
                            </div>
                        </div>
                    </form>
                </div>
                <a href="#x" class="overlay" id="amply"></a>
                <div class="benefits">
                    <div style="padding: 15px;">
                        {include file='product/benefits.tpl'}
                    </div>
                    <a class="close"title="Закрыть" href="#close"></a>
                </div>
            {else}
                <div class="page_text">
                    <p>
                        Ваша корзина пуста!
                    </p>
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