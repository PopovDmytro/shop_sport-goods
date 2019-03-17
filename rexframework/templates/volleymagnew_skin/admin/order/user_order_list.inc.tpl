{strip}
<div class="padding-both">
    <a href="#" class="ui-state-default ui-corner-all link-order">Привязать заказы</a>
</div>
{if $userOrders}
    <div class="table_wrapper">
        <div class="table_hghg_inner order-limit">
            <h3>Заказы</h3>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 98%;" class="user-orders">
                <thead>
                <th>№</th>
                <th>ID</th>
                <th>Общая сумма со скидкой</th>
                <th>Общая скидка</th>
                <th>Комментарии</th>
                <th>Статус</th>
                <th>Привязка к пользователю</th>
                </thead>
                <tbody>
                {foreach from=$userOrders key=key item=order name=orders_list}
                <tr {if $order.status eq 6} class="canceled-order" {/if}>
                    <td><strong>{$smarty.foreach.orders_list.index + 1}</strong></td>
                    <td>{$order.id}</td>
                    <td style="text-align:center">{$order.prices.sale_price}грн
                    <td style="text-align:center">{if $order.prices.sale}{$order.prices.sale}%{else}нет{/if}</td>
                    <td>
                        {if $order.comment}<div class="user-comment-block"><strong>Пользователь:</strong> {$order.comment}</div>{/if}
                        {if $order.admin_comment}<div class="user-comment-block"><strong>Менеджер:</strong> {$order.admin_comment}</div>{/if}
                    </td>
                    <td>{$order.status_text}</td>
                    <td class="action"><input type="checkbox" data-order-id="{$order.id}" class="bing-order-action" {if $order.user_id neq 0}checked{/if}></td>
                </tr>
                {if $order.items}
                <tr class="order-items-list">
                    <td class="popup-inner-table-fix" colspan="20">
                        <table cellpadding="0" cellspacing="0" border="0" style="width: 99%;">
                            <thead>
                            <th>№</th>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Артикул</th>
                            <th>Цена</th>
                            </thead>
                            <tbody>
                            {foreach from=$order.items key=key item=order_item name=order_items}
                            <tr>
                                <td><strong>{$smarty.foreach.order_items.index + 1}</strong></td>
                                <td>{$order_item.product->id}</td>
                                <td>
                                    {if $order_item.imagesku.id}
                                    <img src="{getImg type=icon name=pImage id=$order_item.imagesku.id ext=$order_item.imagesku.image}"/>
        </div>
        {elseif isset($order_item.image.image)}
        <div style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
            <img src="{getImg type=icon name=pImage id=$order_item.image.image.id ext=$order_item.image.image.image}"/>
        </div>
        {else}
        <div style="display:inline-block;float:left;position:relative;width:90px;margin: 3px 5px;">
            <img src="/content/images/pimage/list_mini.jpg"/>
        </div>
        {/if}
        </td>
        <td>{$order_item.product->name}</td>
        <td>{$order_item.sku_article}</td>
        <td>{$order_item.skuprice}</td>
        </tr>
        {/foreach}
        </tbody>
        </table>
        </td>
        </tr>
        {/if}
        {/foreach}
        </tbody>
        </table>
    </div>
    </div>
{else}
    <p>У пользователя нет заказов!</p>
{/if}
{/strip}
{literal}
<script>
    var userBlockClass = 'user-block-detail';
    $(document).on('click', 'table.user-orders > tbody > tr > td:not(.action)', function(e) {
        e.preventDefault();
        var $this = $(this),
            nextRow = $this.parent().next();

        if (nextRow.hasClass('order-items-list')) {
            var elem = nextRow[0];
            if (!elem.style.display.length || elem.style.display === 'none') {
                nextRow.show();
            } else {
                nextRow.hide();
            }
        }
    });

    $(document).on('click', '.link-order', function(e) {
        e.preventDefault();
        var userID = {/literal}{if $userID}{$userID}{else}false{/if}{literal};
        if (!userID) {
            return false;
        }

        $.showRexDialog('order', 'linkedOrders', {user_id: userID}, 'linked_orders', 'Привязка новых заказов к пользователю с ID: ' + userID);
        $('.new-admin-orders').multiselect();
    });

    $(document).on('click', '.linked-orders-submit', function(e) {
        e.preventDefault();
        var $this  = $(this),
                select = $this.parents('.linked-orders-form').find('select.new-admin-orders'),
                userID = select.length ? select.data('user-id') : false;

        if (!select.val().length) {
            alert('Перенесите из правой части один или несколько заказов!');
            return false;
        }

        $.rex('order', 'addLinedOrders', {user_id: userID, linked_orders: select.val()}, function (response) {
            if (response !== false) {
                $.closeRexDialog('linked_orders');
                $.closeRexDialog('client_block_user');
            }
        });
    });

    $(document).on('change', '.bing-order-action', function(e) {
        e.preventDefault();
        var userID  = {/literal}{if $userID}{$userID}{else}false{/if}{literal},
                orderID = $(this).data('order-id') || false;

        if (!userID || !orderID) {
            return false;
        }

        $.rex('order', 'changeOrderBind', {user_id: userID, order_id: orderID, bind_status: +this.checked});
    });

    function getUserRow(className, content) {
        return '<tr class="' + userBlockClass + ' ' + className + '"><td colspan="20"><div class="scroll-fix">' + content + '</div></td></tr>';
    }
</script>
{/literal}