<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Профиль</a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Заказы</a>
            </li>
        </ul>
    </div>
</div>

<section class="profile-tabs">
    <div class="row align-center">
        <div class="columns small-12 large-8">
            <div class="profile_tabs-container product-def">
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
                            <br />
                            <div class="table-block-top">У вас нет завершенных заказов. Статистика и размер скидки будут доступны после успешного завершения первого заказа.</div>
                            <br />
                        {/if}
                        <div class="orders-list-div">
                            {foreach from=$orderList key=key item=order}
                                {include file="order/orders.list.div.tpl" order = $order}
                            {/foreach}
                        </div>
                    {else}
                        У Вас еще нет заказов.
                    {/if}
                </div>
                <div class="product-def-bottom-bg"></div>
            </div>
        </div>
    </div>
</section>