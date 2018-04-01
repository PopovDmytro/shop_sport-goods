{strip}
<div class="linked-orders-form">
    <select class="new-admin-orders" data-user-id="{$userID}" multiple="multiple" name="linked_orders[]">
        {foreach from=$linkedOrders key=key item=order}
            <option title="{$order.text_status}" value="{$order.id}">{$order.id} | {$order.text_status} | {$order.user_name} | {$order.phone}</option>
        {/foreach}
    </select>
    <div class="submit-wrapper">
        <a href="#" class="ui-state-default ui-corner-all linked-orders-submit">Готово</a>
    </div>
</div>
{/strip}