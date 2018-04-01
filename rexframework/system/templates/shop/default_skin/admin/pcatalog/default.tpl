{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.datagrid_container').bind('updateDatagrid', function () {
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('.order_up').die('click').live('click', function(){
        $.rex(mod, 'order', {task: $(this).attr('id'), value: 'up'}, function(data) {
            if (data !== false) {
                $.data(template, 'updateDatagrid')();
            }
        });
    });
    
    template.find('.order_down').die('click').live('click', function(){
        $.rex(mod, 'order', {task: $(this).attr('id'), value: 'down'}, function(data) {
            if (data !== false) {
                $.data(template, 'updateDatagrid')();
            }
        });
    });
    
    template.find('.attributes').die('click').live('click', function() {
        var category_id = $(this).attr('category_id');
        $.showRexDialog('attr2Cat', false, {category_id: category_id, in_parent: true}, 'attributes', 'Attributes');
    });
    
    template.find('.itemadd').unbind('click').click(function(){
        var pid = template.find('#pid').val();
        $.showRexDialog(mod, 'add', {pid: pid}, 'add_'+mod, 'Add ' + mod);
    });
    
    template.find('.ismenu').die('click').live('click', function(){
        var data = $.rex(mod, 'isMenu', {task: $(this).attr('item_id')});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
    
    template.find('.showmain').die('click').live('click', function(){
        var data = $.rex(mod, 'showMain', {task: $(this).attr('item_id')});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
{/literal}
{rexscript_stop}
</script>