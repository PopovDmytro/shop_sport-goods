{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}   
    template.find('.itemadd').unbind('click').click(function(){
        var order_id = $(this).attr('order_id');
        $.showRexDialog(mod, 'add', {order_id: order_id}, 'add_'+mod, 'Add ' + mod);
    });
{/literal}
{rexscript_stop}
</script>