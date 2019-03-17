<div class="row profile_orders-box order-b">
    <div class="order-list-table columns small-12 profile_order_header">
        <div class="row align-justify">
            <div class="column shrink">
                <dl class="order-number order-list-table-id">
                    <dt>№ заказа:</dt>
                    <dd>{$order.id}</dd>
                </dl>
            </div>
            <div class="column shrink">
                <date>{$order.date_create|date_format:"%d.%m.%Y"}</date>
            </div>
        </div>
        <div class="row">
            <div class="column">
                <b class="status {if $order.status eq "Завершен"} blue {/if} {if $order.status eq "Отменен"} red {/if}">{if $order.status eq 'Новый'}Заказ оформлен и отправлен на обработку{elseif $order.status eq 'Проверен'}Проверен и оплачен{else}{$order.status}{/if}</b>
            </div>
        </div>
    </div>

    {if $order.productList}
        {foreach from=$order.productList key=key item=list}
            {assign var=prod2Order 	value=$list.prod2Order}
            {assign var=product 	value=$list.product}
            {assign var=image 		value=$list.image}
            {assign var=attributes 	value=$list.attributes}
            {assign var=imagesku    value=$list.imagessku}
            {assign var=prices      value=$list.prices}

            <div class="columns small-12 profile_order_body">
        <div class="row order_details-container align-middle">
            <div class="columns small-12 medium-6">
                <div class="row">
                    <div class="columns shrink">
                        <div class="pic-holder">
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
                        </div>
                    </div>
                    <div class="columns">
                        <a class="product-name" href="{if $pismo}{$pismo}{/if}{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">{$product->name}</a>
                    </div>
                </div>
            </div>

            <div class="columns shrink order-details">
                <dl>
                    <dt>Артикул:</dt>
                    <dd>
                        {if $prod2Order.sku}
                            {if $list.skuEntity->sku_article}
                                {$list.skuEntity->sku_article}
                            {else}
                                {$prod2Order.sku}
                            {/if}
                        {else}
                            {$product->id}
                        {/if}
                    </dd>
                </dl>
                {if $attributes}
                    {foreach from=$attributes key=attributeKey item=attributeValue}
                        {assign var=attr_key value=$attributeValue.key}
                        {assign var=attr_value value=$attributeValue.value}
                        <dl>
                            <dt class="cart-attr-l">{$attr_key->name}</dt>
                            <dd class="cart-attr-r">{$attr_value->name}</dd>
                        </dl>
                    {/foreach}
                {/if}
                {if $list.sku}
                    <dl>
                        {$list.sku}
                    </dl>
                {/if}
                <dl>
                    <dt>Количество:</dt>
                    <dd>{$prod2Order->count}</dd>
                </dl>
                <dl>
                    <dt>Цена:</dt>
                    <dd>
                        {if $prices.discount gt 0}
                            <span >{$prices.user_price} грн</span>
                        {else}
                            <span>{$prices.price} грн </span>
                        {/if}
                    </dd>
                </dl>
            </div>
        </div>
    </div>
        {/foreach}

        <div class="columns small-12 profile_order_footer">
            <div class="row align-middle">
                <div class="columns small-12 medium-6">
                    <div id="order-price">
                        <span class="order-price" item-id="{$order.id}">
                            {if $order.sale_price < $order.full_price}
                                {if $order.sale gt 0}
                                    <span >Ваша скидка на заказ {$order.sale}%</span><br>
                                {/if}
                                <br>Общая сумма к оплате: {$order.sale_price} грн.
                            {else}
                                Общая сумма к оплате: {$order.full_price} грн.
                            {/if}
                        </span>
                    </div>
                </div>
                {if $order.comment and $order.type == NULL}
                    <div class="columns small-12 medium-6">
                        <dl>
                            <dt>Примечание:</dt>
                            <dd>{$order.comment|strip_tags}</dd>
                        </dl>
                    </div>
                {/if}
                {if $order.type != NULL}
                    <div class="columns small-12 medium-6">
                        <dl>
                            <dt>
                                Статус заказа: <i>{$order.status}</i>
                                <br />
                                Примечание: {$order.comment|replace:"\n":"<br/>"}
                            </dt>
                            <dd>Стоимость товара в Украине: {if $order.price > 0}{$order.price}{if $order.type == 1} грн{elseif $order.type == 2} у.е.{/if}{else}Уточняется{/if}</dd>
                        </dl>
                    </div>
                {/if}
            </div>
        </div>
    {/if}
</div>