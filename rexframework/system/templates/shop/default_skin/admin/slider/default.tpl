{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}

	template.find('.datagrid_container').bind('updateDatagrid', function () {
        $.data(template, 'updateDatagrid')();
    });

    template.on('click', '.order_up' ,function(){
        $.rex(mod, 'order', {task: $(this).attr('id'), value: 'up'}, function(data) {
            if (data !== false) {
                $.data(template, 'updateDatagrid')();
            }
        });
    });

    template.on('click', '.order_down', function(){
        $.rex(mod, 'order', {task: $(this).attr('id'), value: 'down'}, function(data) {
            if (data !== false) {
                $.data(template, 'updateDatagrid')();
            }
        });
    });
{/literal}
{rexscript_stop}
</script>