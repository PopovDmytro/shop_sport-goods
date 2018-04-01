<table cellpadding="0" cellspacing="0" border="0" class="order-list-table-down">
{foreach from=$dataList key=key item=list}
	{assign var=prod2Order 	value=$list.prod2Order}
	{assign var=product 	value=$list.product}
	{assign var=category 	value=$list.category}
	{assign var=image 		value=$list.image}
    {assign var=attributes     value=$list.attributes}
	<tr>
		<td class="order-list-table-down-count" valign="top">
			<div class="cart-title"><a class="view-product" item_id="{$product.id}" href="javascript: void(0);">{$product.name}</a></div>
			<div class="cart-price">Стоимость:{if isset($list.skuprice)}{if $valuta eq 'грн'}{($list.skuprice*$dolar_rate)|round:1} грн.{elseif $valuta eq '$'}${$list.skuprice|round:1}{/if}{elseif $valuta eq 'грн'}{($product.price*$dolar_rate)|round:0} грн.{elseif $valuta eq '$'}${$product.price|round:0}{/if}</div>
			<div class="cart-price"> Артикул: {$product.id}{if $prod2Order.sku}-{$prod2Order.sku}{/if}</div>
			<table cellpadding="0" cellspacing="0" border="0" class="cart-attr">
			{if $attributes}
				{foreach from=$attributes key=attributeKey item=attributeValue}
					{assign var=attr_key value=$attributeValue.key}
					{assign var=attr_value value=$attributeValue.value}
					<tr>
						<td class="cart-attr-l">{$attr_key.name}</td>
						<td class="cart-attr-r">{$attr_value.name}</td>
					</tr>
				{/foreach}
			{/if}
            {if $list.sku}
                <tr>
                    {$list.sku}
                </tr>
            {/if}
			<tr>
				<td class="cart-attr-l">Количество</td>
				<td class="cart-attr-r">{$prod2Order.count}</td>
			</tr>
			</table>
            {if $list.imagesku.id}
                <div class="rrr" style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
                    <img src="{getimg type=icon name=pImage id=$list.imagesku.id ext=$list.imagesku.image}"/>
                </div>
            
            {elseif isset($image.image)}
                <div style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
                    <img src="{getimg type=icon name=pImage id=$image.id ext=$image.image}"/>
                </div>
            {/if}
            
		</td>
	</tr>
{/foreach}
</table>