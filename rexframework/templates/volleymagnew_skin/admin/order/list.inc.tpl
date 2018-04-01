<table cellpadding="0" cellspacing="0" border="0" class="order-list-table-down order-list" id="order-list-{$id}" style="padding:7px;">
<tr>
    <td class="order-info-big">
        <table class="order-info">
            <tr class="order-info-inside order_user" >
                <td id="right">Пользователь:</td>
                <td>{$orderInfo.name} {$orderInfo.name_single}</td>
            </tr>
            <tr class="order-info-inside">
                <td id="right">Телефон:</td>
                <td>{$orderInfo.phone}</td>
            </tr>
            <tr class="order-info-inside">
                <td id="right">Город:</td>
                <td>{$orderInfo.city_name}</td>
            </tr>
            <tr class="order-info-inside">
                <td id="right">Филиал:</td>
                <td >{$orderInfo.fillials_name}</td>
            </tr>
            <tr class="order-info-inside">
                <td id="right">Оплата:</td>
                <td >{$orderInfo.delivery}</td>
            </tr>
        </table>
    </td>
</tr>
{*<tr> 
    <td class="order-info-big">
        Телефон: {$orderInfo.phone}
    </td>
</tr>
<tr> 
    <td class="order-info-big">
        Город: {$orderInfo.city_name}
    </td>
</tr>
<tr>
    <td class="order-info">
        Филиал: {$orderInfo.fillials_name}
    </td>
</tr>*}
{assign var=count       value="1"}
{foreach from=$dataList key=key item=list}
    {assign var=prod2Order   value=$list.prod2Order}
    {assign var=product      value=$list.product}
    {assign var=category     value=$list.category}
    {assign var=image        value=$list.image}
    {assign var=attributes   value=$list.attributes}
    {assign var=prices       value=$list.prices}
    
    <tr>
        <td class="order-list-table-down-count" valign="top" style="vertical-align: middle;">
            {*<div class="cart-title"><a class="view-product" item_id="{$product.id}" href="javascript: void(0);">{$product.name}</a></div>*}
            <div class="count" style="position: absolute; font-size: 14px; font-weight: bold; z-index: 2;">{$count}</div>
            {if $list.imagesku.id}
                <div class="rrr" style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
                    <img src="{getimg type=icon name=pImage id=$list.imagesku.id ext=$list.imagesku.image}"/>
                </div>
            {elseif isset($image.image)}
                <div style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
                    <img src="{getimg type=icon name=pImage id=$image.id ext=$image.image}"/>
                </div>
            {else}
                <div style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
                    <img src="/content/images/pimage/list_mini.jpg"/>
                </div>
            {/if}
            <div class="left_attr_order">
                <div class="cart-name">
                    <b>
                        <a class="view-product" item_id="{$product.id}" target="_blank" href="/product/{$category.alias}/{$product.id}.html">{$product.name}</a>
                    </b>
                </div>
                <div class="cart-price">
                    {if $prices.discount gt 0}
                        {$prices.user_price}грн. <span style="text-decoration:line-through">({$prices.price}грн)</span><br>
                        <span style="font-size:10px">(скидка на товар {$prices.discount}%)</span>
                    {else}
                        {$prices.price}грн.
                    {/if}

                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="cart-attr">
                    <tr style="">
                        <td class="cart-attr-l"><b>Количество&nbsp;</b></td>
                        <td class="cart-attr-r">{$prod2Order.count}</td>
                    </tr>
                    <tr style="">
                        <td class="cart-attr-l"><b>Артикул&nbsp;</b></td>
                        <td class="cart-attr-r">
                            {if $prod2Order.sku}
                                {if $list.sku_article}
                                    {$list.sku_article}
                                {elseif $prod2Order.sku eq 1}
                                    <span class="sku">При заказе не были указаны атрибуты товара</span>
                                {else}
                                    {$prod2Order.sku}
                                {/if}
                            {else}                            
                                {$product.id}
                            {/if}
                        </td>
                    </tr>
                    <input type=hidden class="summa-{$product.id}" value="{$prod2Order.count*$product.price}">
                    {if $attributes}
                        {foreach from=$attributes key=attributeKey item=attributeValue}
                            {assign var=attr_key value=$attributeValue.key}
                            {assign var=attr_value value=$attributeValue.value}
                            <tr>
                                <td class="cart-attr-l"><b>{$attr_key.name}</b></td>
                                <td class="cart-attr-r">{$attr_value.name}</td>
                            </tr>
                        {/foreach}
                    {/if}
                    {if $list.sku}
                        <tr>
                            {$list.sku}
                        </tr>
                    {/if}
                </table>
            </div>
        </td>
    </tr>
    {assign var=count value=$count+1}
{/foreach}
<tr>
    <td class="list-table-down-count">
        <div class="allprice">
            <span style="color:#BFB9B9">Общая сумма заказа: {$order_values.full_price}грн</span><br />
            Общая скидка на заказ: {if $order_values.sale}{$order_values.sale}%{else}нет{/if}<br />
            <span style="font-weight:bold; color:#000">Сумма к оплате: {$order_values.sale_price}грн</span><br />
        </div>
    <td>
</tr>
<tr>
    <td>
        <b style="font-size:14px">Примечание:</b> <span style="font-size:14px">{$comment}</span>
    </td>
</tr>
</table>