	<div class="product-def">
            <div class="nav_category bgcolor round">
                <p class="navigation-p">                                      
                {strip}
                    Заказы
                {/strip}
                </p>
            </div>
			<div class="into-box">
            
			{if $orderList}
				<div class="orders-list-div">
					{foreach from=$orderList key=key item=order}
					<hr>
						<div class="order-b">
							<table cellpadding="0" cellspacing="0" border="0" class="order-list-table">
								<tr>
									<td class="order-list-table-id" valign="top"><b>№ заказа:</b> {$order.id}</td>
                                    <td class="order-list-table-date" valign="top"><b>Дата:</b> {$order.date_create|date_format:"%d/%m/%Y"} &nbsp;&nbsp;&nbsp;
                                    <b style="color:green; float:right;">{if $order.status eq 'Новый'}Заказ оформлен и отправлен на обработку{/if}</b></td>
								</tr>
							{if $order.productList}
									{foreach from=$order.productList key=key item=list}
										{assign var=prod2Order 	value=$list.prod2Order}
										{assign var=product 	value=$list.product}
										{assign var=image 		value=$list.image}
										{assign var=attributes 	value=$list.attributes}
                                        {assign var=imagesku     value=$list.imagessku}
										<tr>
											<td valign="top" width="140" class="order-list-table-down-img">
												{if isset($imagesku.id)}
                                                    <a href="{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">
                                                        <img src="{getimg type=icon name=pImage id=$imagesku.id ext=$imagesku.image}"/>
                                                    </a>
                                                {elseif isset($image.image)}
                                                     <a href="{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">
                                                        <img src="{getimg type=icon name=pImage id=$image.id ext=$image.image}"/>
                                                    </a>
                                                {else}
                                                      {img src="default-icon-60.jpg" class="t-image"}
                                                {/if}
											</td>
											
											<td class="order-list-table-down-count" valign="top">
												<div class="cart-title"><a href="{url mod=product act=default cat_alias=$list.img_alias task=$product->id}">{$product->name}</a></div>
												<div class="cart-price"> <b>Артикул:</b> {$product->id}{if $prod2Order.sku}-{$prod2Order.sku}{/if}</div>
												<div class="cart-price"> <b>Стоимость:</b> <span class="new-price">{$product->price} грн.</span></div>
												<table cellpadding="0" cellspacing="0" border="0" class="cart-attr">
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
													<tr>
														<td class="cart-attr-l"><b>Количество:&nbsp;</b></td>
														<td class="cart-attr-r">{$prod2Order->count}</td>
													</tr>
													<tr>
														<td class="cart-attr-l"><b>Статус заказа:</b></td>
														<td class="cart-attr-r"><span class="new-price"><i>{$order.status}</i></span></td>
													</tr>
												</table>
											</td>
										</tr>
									{/foreach}
								</table>
							{/if}
							{if $order.comment and $order.type == NULL}
							<table cellpadding="0" cellspacing="0" border="0" class="order-list-table-c">
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
					{/foreach}
				</div>
            {else}
                У Вас еще нет заказов. 
			{/if}
		</div>
	<div class="product-def-bottom-bg"></div>
	</div>