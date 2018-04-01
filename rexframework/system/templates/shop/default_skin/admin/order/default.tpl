{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.view-product').die('click').live('click', function() {
        var product_id = $(this).attr('item_id');
        $.showRexDialog('product', 'edit', {task: product_id, view: true}, 'view_product', 'View Product');
    });
    
    template.find('.user-info').die('click').live('click', function() {
        var user_id = $(this).attr('user_id');
        $.showRexDialog('user', 'edit', {task: user_id, in_parent: true}, 'show_user', 'User info');
    });
    
    $.rexDialog('view_product').find('.item_show').die('click').live('click', function(){
        $.closeRexDialog('view_product');
    });
{/literal}
{rexscript_stop}
</script>