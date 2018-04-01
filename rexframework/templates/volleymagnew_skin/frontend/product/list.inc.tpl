{if $productList}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td class="rightblock-title" style="height:20px">мы рекомендуем</td>
</tr>

{foreach from=$productList key=key item=product}
{assign var=product_id value=$product.id}
<tr>
	<td>
		{strip}
		<p class="rightblock-img">
		<a href="http://{$DOMAIN}/product/{$product.id}.html">
			{if $imageList.$product_id}
				{assign var=image value=$imageList.$product_id}
				<img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>
			{else}
                {img src="default-icon-60.jpg"}
			{/if}
		</a>
		</p>
		{/strip}
		<p class="rightblock-name"><a href="http://{$DOMAIN}/product/{$product.id}.html">{$product.name}</a></p>
	</td>
</tr>
{/foreach}

</table>
{/if}