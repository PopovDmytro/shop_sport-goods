<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item">
                <a href="{url mod=user id=$user->id}" class="breadcrumbs_link">Профиль</a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Заказы</a>
            </li>
        </ul>
    </div>
</div>

    <div class="product-def">
        {include file="user/intobox.text.tpl"}
			<div class="into-box">
			{if $orderList}
                {if $o_count}
                    <table class="table-block">
                        <tr>
                            <td>
                                <div>Количество завершенных заказов: </div>
                            </td>
                            <td>
                                <div>Успешно завершены заказы на сумму: </div>
                            </td>
                            <td>
                                <div>Сэкономлено с VolleyMAG: </div>
                            </td>
                            <td>
                                <div>Постоянная скидка на последующие заказы: </div>
                            </td>
                        </tr>
                        <tr id="values">
                            <td>
                                <div>{$o_count}</div>
                            </td>
                            <td>
                                <div>{if $sale.discounted}{$sale.discounted}{/if} грн</div>
                            </td>
                            <td>
                                <div>{if $sale.undiscounted gt $sale.discounted}{$sale.undiscounted - $sale.discounted} грн{else}Учет скидки начнется со следующей покупки!{/if}</div>
                            </td>
                            <td>
                                <div>{if $userSale}{$userSale}{else}0{/if}%</div>
                            </td>
                        </tr>
                    </table>
                {else}
                    <div class="table-block-top" style="display: inline-block;">У вас нет оплаченных товаров. Статистика и размер скидки будут доступны после оплаты заказа.</div>
                {/if}
				<div class="orders-list-div">
					{foreach from=$orderList key=key item=order}
                        {*<span class="hr"></span>*}
						{include file="order/orders.list.div.tpl"  order = $order}
					{/foreach}
				</div>
                {*<div class="order_sum">Всего заказов на сумму: {$order_sum} грн.</div>
                {if $order_count >= 1}
                <div class="order_sum_sale">Всего заказов на сумму(со скидкой): {$order_sum_notsale} грн.</div>

                    <div class="order_sum_ekonom">Вы сэкономили: {$order_sum-$order_sum_notsale} грн.</div>
                <div class="sale_user">Размер вашей скидки: {if $order_sum > 5000}10%{elseif $order_sum > 2000}5%{elseif $order_sum > 1000}3%{elseif $order_count >= 1}2%{/if}</div>
                {else}
                <div class="sale_user">У вас пока что нет скидки!</div>
                {/if*}
            {else}
                У Вас еще нет заказов.
			{/if}
		</div>
	<div class="product-def-bottom-bg"></div>
	</div>