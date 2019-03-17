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
                            <div class="orders-description">
                                <div><b>Количество завершенных заказов: </b><span>{$o_count}</span></div>
                                <div><b>Успешно завершены заказы на сумму: </b><span>{if $sale.discounted}{$sale.discounted}{/if} грн</span></div>
                                <div><b>Сэкономлено с VolleyMAG: </b>
                                    <span>
                                        {if $sale.undiscounted gt $sale.discounted}
                                            {*{$sale.undiscounted - $sale.discounted} грн*}
                                            {$saveWith} грн
                                        {else}
                                            Учет скидки начнется со следующей покупки!
                                        {/if}
                                    </span>
                                </div>
                                <div><b>Постоянная скидка на последующие заказы: </b><span>{if $userSale}{$userSale}{else}0{/if}%</span></div>
                            </div>
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