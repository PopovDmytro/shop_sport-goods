<img class="title-image" src="/content/skin/{$rexPage->skin}/images/new-product.jpg" alt="" />
{foreach from=$product_new key=key item=item}
	<div class="product">
		<table height="189px" width="100%" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="3" class="td-product-t"></td>
			</tr>
			<tr>
				<td class="td-product-l"></td>
				<td valign="top" class="td-product-c">
					<div class="prod-name"><a href="http://{$DOMAIN}/product/{$item.id}.html"><h3>{$item.name|truncate:30:"..."}</h3></a></div>
					<div class="product-c">
						{if $images}
							{foreach from=$images key=images_key item=images_item}
								{if $item.image_id == $images_item.id}
									<a href="http://{$DOMAIN}/product/{$item.id}.html">
                                    <img src="{getimg type=list name=pImage id=$images_item.id ext=$images_item.image}"/>
								{/if}
							{/foreach}
						{else}
							<a href="http://{$DOMAIN}/product/{$item.id}.html">
                            {img src="default_icon.jpg"}
                            </a>
						{/if}
					</div>
					<div>
						<div class="price">
							
						</div>
						<div class="more-link">
							<a href="http://{$DOMAIN}/product/{$item.id}.html">Подробнее</a>
						</div>
					</div>
				</td>
				<td class="td-product-r"></td>
			</tr>
			<tr>
				<td colspan="3" class="td-product-b"></td>
			</tr>
		</table>
	</div>
{/foreach}