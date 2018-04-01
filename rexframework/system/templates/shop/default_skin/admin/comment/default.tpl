{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.itemeactive').die('click').live('click', function(){
        var data = $.rex(mod, 'status', {task: 2, id: $(this).attr('item_id')});
        
        if(data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });

    template.find('.itemdeactive').die('click').live('click', function(){
        var data = $.rex(mod, 'status', {task: 1, id: $(this).attr('item_id')});
        
        if(data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
{/literal}
{rexscript_stop}
</script>