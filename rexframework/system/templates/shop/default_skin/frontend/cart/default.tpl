      <div class="nav_category bgcolor round">
            <p class="navigation-p">                                      
            {strip}
                Корзина
            {/strip}
            </p>
        </div> 
    <div class="product-def">
        <div class="product-def-top-bg"></div>
        {page type='getRenderedMessages'}
        {page type='getRenderedErrors'}

        <div class="into-box">
            {if $cartList}  
                    <ul>
                    <form action="" method="post" id="cartForm">
                    <input type="hidden" name="mod" value="order" id="mod">
                    <input type="hidden" name="act" value="add" id="act">
                                            
                        {assign var=nitem value=1}
                        {foreach from=$cartList key=key item=cart name=cartitem}
                            {assign var=cartEntity value=$cart.cart}
                            {if $cart.product}
                            
        
                                {assign var=productEntity value=$cart.product}
                                {assign var=image value=$cart.image} 
                                {assign var=pcatalog_alias value=$cart.pcatalog_alias}
                                <li class="cart-li" {if !$smarty.foreach.cartitem.last}style="border-bottom:1px solid #D2D2D2"{/if}>
                                    <input type="hidden" name="cart[{$nitem}][product_id]"     value="{$productEntity->id}">
                                    <input type="hidden" name="cart[{$nitem}][attributes]"     value="{$cartEntity->attributes}">
                                    <input type="hidden" name="cart[{$nitem}][sku]"            value="{$cartEntity->sku}">
                                    
                                    <table cellpadding="2" cellspacing="0" border="0" width="100%">
                                        <tr>
                                            <td valign="top" width="130px;" class="cart-img">
                                                <a href="{url mod=product act=default cat_alias=$pcatalog_alias task=$productEntity->id}">
                                                    {if $image}
                                                        <img src="{getimg type=icon name=pImage id=$image.id ext=$image.image}" />
                                                    {else}
                                                          {img src="default-icon-120.jpg" class="t-image"}
                                                    {/if}
                                                </a>
                                            </td>
                                            <td valign="top">
                                                <div class="cart-title"><a href="{url mod=product act=default cat_alias=$pcatalog_alias task=$productEntity->id}">{$productEntity->name}</a></div>
                                                {if $filter_kurs eq 'грн'}
                                                    <div class="cart-price"> Стоимость: <span class="price-us">{assign var=dolar_rate value='dolar_rate'|settings}{$productEntity->price*$dolar_rate|round:2}грн.</span></div>
                                                {else}
                                                    <div class="cart-price"> Стоимость: <span class="price-us">${$productEntity->price|round:2}</span></div>
                                                {/if}
                                                <span item-id="{$productEntity->id}{if $cartEntity->sku}-{$cartEntity->sku}{/if}" class="cart-price" style="display: none;">{$productEntity->price*$dolar_rate|round:0}</span>
                                                <table cellpadding="0" cellspacing="0" border="0" class="cart-attr">
                                                    {if $cart.attributes}
                                                        {foreach from=$cart.attributes key=attributeKey item=attributeValue}
                                                            {assign var=attr_key value=$attributeValue.key}
                                                            {assign var=attr_value value=$attributeValue.value}
                                                            <tr>
                                                                <td class="cart-attr-l">{$attr_key->name}</td>
                                                                <td class="cart-attr-r">{$attr_value->name}</td>
                                                            </tr>
                                                        {/foreach}
                                                    {/if}
                                                    {if $cart.sku}
                                                        <tr>
                                                            {$cart.sku}
                                                        </tr>
                                                    {/if}
                                                    <tr>
                                                        <td class="cart-attr-l">Количество</td>
                                                        <td class="cart-attr-r"><input onkeyup="javascript:backspacerUP(this,event);" onkeydown="javascript:backspacerDOWN(this,event);" item-id="{$productEntity->id}{if $cartEntity->sku}-{$cartEntity->sku}{/if}" class="cart-amount" type="text" name="cart[{$nitem}][count]" value="{$cartEntity->count}"></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2"><a class="a-button bgcolor roundp" {if $cartEntity->sku} href="{url mod=cart act=clear id=$productEntity->id sku=$cartEntity->sku}"{else}href="{url mod=cart act=clear id=$productEntity->id}"{/if}>Удалить</a></td>
                                                    </tr>
                                                </table>
                                            </td> 
                                        </tr>
                                    </table>
                                </li>
                                {assign var=nitem value=$nitem + 1}
                            {/if}
                        {/foreach}
                    <hr>
                    <span style="float:right"><b>Всего: <span class="cart-alltogether"></span> грн</b><br></span>
                    <table cellpadding="0" cellspacing="0">
                        {if !$user}
                            <tr>
                                <td class="cart-attr-l" colspan="2"><p class="cart-prim">Имя:</p></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l" colspan="2"><input type="text" name="order[name]" value=""></td>
                            </tr>
                            
                            <tr>
                                <td class="cart-attr-l" colspan="2"><p class="cart-prim">Телефон:</p></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l" colspan="2"><input type="text" name="order[phone]" value="" id="phone" maxlength="24" data-init-inputmask="true" placeholder="+38(___)___-__-__"></td>
                            </tr>
                        
                        {/if}
                            <tr>
                                <td class="cart-attr-l" colspan="2"><p class="cart-prim">Примечание:</p></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l" colspan="2"><textarea class="cart-textarea" name="order[comment]"></textarea></td>
                            </tr>
                            <tr>
                                <td class="cart-attr-l-create" colspan="2" align="left">
                                <input type="button" class="cart-clear bgcolor round" onclick="document.location.href='/'" value="Вернуться к покупкам">
                                <input type="button" class="cart-clear bgcolor round" onclick="document.location.href='{url mod=cart act=clear}'" value="Очистить корзину">
                                <input type="submit" class="cart-create bgcolor round" name="cart[submit]" value="Оформить заказ" id="order-submit-button"></td>
                            </tr>
                        </table>
                    </form>
                </ul>
            {else}
                Ваша корзина пуста!
            {/if}
        </div>

    </div>


{include file="_block/input.phone.mask.tpl"}
<script language="javascript">
{rexscript_start}
{literal}
        $(document).ready(function(){
            initPhoneMask();
        });

        $('.cart-amount').unbind('keyup').bind('keyup', function(event){
            var alltogether = 0;
            $('.cart-amount').each(function(){
                var id = $(this).attr('item-id');
                var price = $('.cart-price[item-id=' + id + ']').text();
                var count = $(this).val();   
               // console.log($('.cart-price[item-id=' + id + ']').text());   
                alltogether += count * price;
            });
            $('.cart-alltogether').text(Math.round(alltogether));
        });
        /*$('.cart-amount').keyup();
        template.find('.cart-create').die('click').live('click', function(){
             var text = $('#phone').val();
             var num = $.isNumeric(text);
             if((!text.length > 3) && !num){
                 alert('Ошибка в идентификаторе пользователя, необходимо указать хотя бы телефон(не меньше 3 символов)');
                 return false;
             }
         });*/
         $('#order-submit-button').die('click').live('click', function(){
            var text = $('#phone').val();
            var num = $.isNumeric(text);
            if(text.length !== 12 || !num){
                alert('Ошибка в идентификаторе пользователя, необходимо указать хотя бы телефон(в формате 380ХХХХХХХХХ). Номер должен состоять из 12 цифр');  
                return false;
            } else if ($('#name').length && $('#name').val().length < 3) {
                alert('Некорректное имя пользователя'); 
                return false;  
            } else {
                $('#cartForm').append('<input type="hidden" name="cart[submit]" value="1">'); 
            } ; 
        });

{/literal}  
{rexscript_stop} 
</script>