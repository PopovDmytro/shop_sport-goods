{include file="parent/default.tpl"}
<script type="text/javascript">
    {rexscript_start}
    {literal}
    template.on('click', '.itemeactive', function() {
        var data = $.rex(mod, 'active', {task: $(this).attr('item_id'), value: 1});
        if(data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.on('click', '.itemdeactive', function() {
        var data = $.rex(mod, 'active', {task: $(this).attr('item_id'), value: 0});
        if(data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.on('click', '.send-sms-link', function(e){
        e.preventDefault();
        $.showRexDialog('user', 'sendSms', {task: $(this).attr('user_id')}, 'sendsms', 'Sms for user '+$(this).attr('user_id'));
    });

    template.on('click', '.user-orders-sum', function(e) {
        e.preventDefault();

        template.find('#orders_sum').val('orders_sum');
        $.data(template, 'updateDatagrid')();
    });

    var clearFilters = function() {
        template.find('#user_list').val('');
        template.find('#user_id').val('');
        template.find('.search').val('');
        $.data(template, 'updateDatagrid')();
    };

    $.data(template, 'clearFilters', clearFilters);


    $("#user_list").autocomplete("/admin/autocompleteusers/", {
        selectFirst: false,
        resultsClass: 'ac_results user_auto',
        minLength: 2,
        width: 300,
        scrollHeight: 400,
        max: 20,
        formatItem: function(data, i, n, value) {
            user_id = value.split('=')[0];
            user_name = value.split('=')[1];
            user_lastname = value.split('=')[2];
            user_phone = value.split('=')[3];

            return '<div style="padding-top: 13px;color: #089BE3;">'+user_name+' '+user_lastname+' ('+user_phone+')</div>'

        }, formatResult: function(data, i, n) {
            return i.split('=')[1];
        }
    }).result(function(event, item) {
        $('#user_list').val(item[0].split('=')[1]);
        template.find('#user_id').val(item[0].split('=')[0]);
        $.data(template, 'updateDatagrid')();
        return false;
    });

    $(document).on('click', 'table.data:not(.user-orders) > tbody > tr:not(.user-block-detail):not(:first-child) > td:not(.last)', function() {
        var userID = $(this).parent().find('td:first').text();
        if (!userID) {
            alert('Не удалось загрузить данные пользователя');
            return false;
        }

        userID = parseInt(userID);
        $.showRexDialog(mod, 'edit', {task: userID, only_view: true}, 'client_block_' + mod, 'Блок клиента');
    });

    {/literal}
    {rexscript_stop}
</script>