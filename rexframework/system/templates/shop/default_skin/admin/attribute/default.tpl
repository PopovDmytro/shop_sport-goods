{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.datagrid_container').bind('updateDatagrid', function () {
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('.order_up').die('click').live('click', function(){
        var data = $.rex(mod, 'order', {task: $(this).attr('id'), value: 'up'}, function () {});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
    
    template.find('.order_down').die('click').live('click', function(){
        var data = $.rex(mod, 'order', {task: $(this).attr('id'), value: 'down'}, function () {});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });    
    
    template.find('.itemadd').unbind('click').click(function(){
        var pid = template.find('#pid').val();
        $.showRexDialog(mod, 'add', {pid: pid}, 'add_'+mod, 'Add ' + mod);
    });
{/literal}
{rexscript_stop}
</script>