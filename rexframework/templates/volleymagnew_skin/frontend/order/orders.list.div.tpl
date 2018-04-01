<div class="order-b">
    <table cellpadding="0" cellspacing="0" border="0" class="order-list-table">
        <tr>
            <td class="order-list-table-id" valign="top" colspan="5"><b>№ заказа:</b> {$order.id}<span style="float:right; font-size:12px">{$order.date_create|date_format:"%d.%m.%Y"}</span></td>
        </tr>
        <tr>
            <td class="order-list-table-date" valign="top" colspan="5">
            <b style="color:green; float:left;margin-right:20px">{if $order.status eq 'Новый'}Заказ оформлен и отправлен на обработку{elseif $order.status eq 'Проверен'}Проверен и оплачен{else}{$order.status}{/if}</b></td>
        </tr>
        <tr>
            <td valign="top" colspan="5"><span class="hr_dot"></span></td>
        </tr>
    {if $order.productList}
            {foreach from=$order.productList key=key item=list}
                {assign var=prod2Order 	value=$list.prod2Order}
                {assign var=product 	value=$list.product}
                {assign var=image 		value=$list.image}
                {assign var=attributes 	value=$list.attributes}
                {assign var=imagesku    value=$list.imagessku}
                {assign var=prices      value=$list.prices}
                <tr>
                    <td valign="top" width="140" class="order-list-table-down-img">
                        {if isset($imagesku.id)}
                            <a href="{if $pismo}{$pismo}{/if}{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">
                                <img src="{if $pismo}{$pismo}{/if}{getimg type=icon name=pImage id=$imagesku.id ext=$imagesku.image}"/>
                            </a>
                        {elseif isset($image.image)}
                             <a href="{if $pismo}{$pismo}{/if}{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">
                                <img src="{if $pismo}{$pismo}{/if}{getimg type=icon name=pImage id=$image.id ext=$image.image}"/>
                            </a>
                        {else}
                              {img src="default-icon-60.jpg" class="t-image"}
                        {/if}
                    </td>
                    <td class="name-product">
                        <div class="cart-title">
                            <a href="{if $pismo}{$pismo}{/if}{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">{$product->name}</a>
                        </div>
                    </td>
                    <td class="prod-count">
                        <b>Количество:</b> {$prod2Order->count}
                    </td>
                    <td class="order-list-table-down-count" valign="top" style="vertical-align: middle;">

                        <table cellpadding="0" cellspacing="0" border="0" class="cart-attr" style="margin:auto; width: 91%;">
                        <tr>
                            <td>
                                <div class="cart-article"> <b>Артикул:</b> </div>
                            </td>
                            <td>
                                {if $prod2Order.sku}
                                    {if $list.skuEntity->sku_article}
                                        {$list.skuEntity->sku_article}
                                    {else}
                                        {$prod2Order.sku}
                                    {/if}
                                {else}
                                    {$product->id}
                                {/if}
                            </td>
                        </tr>
                            {if $attributes}
                                {foreach from=$attributes key=attributeKey item=attributeValue}
                                    {assign var=attr_key value=$attributeValue.key}
                                    {assign var=attr_value value=$attributeValue.value}
                                    <tr>
                                        <td class="cart-attr-l">{$attr_key->name}</td>
                                        <td class="cart-attr-r">{$attr_value->name}</td>
                                    </tr>
                                {/foreach}
                            {/if}
                            {if $list.sku}
                                <tr>
                                    {$list.sku}
                                </tr>
                            {/if}
                        </table>
                    </td>
                    <td class="prod-price">
                        <div class="cart-price">
                            <span class="new-price">
                                {if $prices.discount gt 0}
                                    {*<span class="price-not-sale">{$prices.price} грн</span>*}
                                    <span style="color:#008000; font-weight:bold;">{$prices.user_price} грн.</span>
                                    {*<br>
                                    <span>Скидка на товар {$prices.discount}%</span> *}
                                {else}{$prices.price} грн
                                {/if}
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td valign="top" colspan="5"><span class="hr_dot"></span></td>
                </tr>
            {/foreach}
        </table>

        <div id="order-price">
            <span class="order-price" item-id="{$order.id}">
                {if $order.sale_price < $order.full_price}
                    {if $order.sale gt 0}
                        <span style="font-size:11px">Ваша скидка на заказ {$order.sale}%</span><br>
                    {/if}
                    <br>Общая сумма к оплате: {$order.sale_price} грн.
                    {*<span class="order-not-sale">Без скидки: {$order.full_price} грн</span>*}
                {else}
                    Общая сумма к оплате: {$order.full_price} грн.
                {/if}
            </span>
            <br/>
        </div>
    {/if}
    {if $order.comment and $order.type == NULL}
    <table cellpadding="0" cellspacing="0" border="0" class="order-list-table-com">
        <tr>
            <td class="order-list-table-comment"><b>Примечание:</b></td>
        </tr>
        <tr>
            <td class="order-list-table-comment">{$order.comment|strip_tags}</td>
        </tr>
    </table>
    {/if}
    {if $order.type != NULL}
    <table cellpadding="0" cellspacing="0" border="0" class="cart-attr">
        <tr>
            <td class="cart-price"><b>Статус заказа:</b> <i>{$order.status}</i></td>
        </tr>
        <tr>
            <td class="cart-price"><b>Примечание:</b> {$order.comment|replace:"\n":"<br/>"}</td>
        </tr>
        <tr>
            <td class="cart-price"><b>Стоимость товара в Украине:</b> {if $order.price > 0}{$order.price}{if $order.type == 1} грн{elseif $order.type == 2} у.е.{/if}{else}Уточняется{/if}</td>
        </tr>
    </table>
    {/if}

</div>