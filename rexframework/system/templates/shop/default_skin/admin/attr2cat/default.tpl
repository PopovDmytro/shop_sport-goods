{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.itemadd').unbind('click').click(function(){
        $.showRexDialog(mod, 'add', {category_id: template.find('#category_id').val()}, 'add_'+mod, 'Add ' + mod);
    });
    
    $.rexDialog('add_'+mod).find('.item_add_attr').die('click').live('click', function() {
        var element = new Array();
        var entity = {};
        var selected = $.rexDialog('add_'+mod).find('#attributes-list').find('.select');
                    
        selected.each(function() {
            element.push($(this).attr('theid'));
        });
        
        var category_id = template.find('#category_id').val();
        entity['category_id'] = category_id;
        entity['exist_id'] = '';
        
        if(element.length > 0) {
            $.rex('attr2Cat', 'add', {attributes: element, entity: entity, category_id: category_id});
        }
        
        $.closeRexDialog('add_'+mod);
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('.move_up').die('click').live('click', function(){
        var data = $.rex(mod, 'order', {task: $(this).attr('id'), value: 'up'});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
    
    template.find('.move_down').die('click').live('click', function(){
        var data = $.rex(mod, 'order', {task: $(this).attr('id'), value: 'down'});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
    
    template.find('.forsale').die('click').live('click', function(){
        var data = $.rex(mod, 'forSale', {task: $(this).attr('item_id')});
        if (data !== false) {
            $.data(template, 'updateDatagrid')();
        }
    });
{/literal}
{rexscript_stop}
</script>