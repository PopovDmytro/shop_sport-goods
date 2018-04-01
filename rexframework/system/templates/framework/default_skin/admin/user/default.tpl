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
    
    template.on('click', '.send-sms-link', function(){
        $.showRexDialog('user', 'sendSms', {task: $(this).attr('user_id')}, 'sendsms', 'Sms for user '+$(this).attr('user_id'));
    });
{/literal}
{rexscript_stop}
</script>