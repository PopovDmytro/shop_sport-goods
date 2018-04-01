{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.itemadd').unbind('click').click(function(){
        $.showRexDialog(mod, 'upload', {product_id: template.find('#product_id').val()}, 'attach_upload', 'Upload Files');
    });
    
    $.rexDialog('attach_upload').find('#save_upload').die('click').live('click', function(){
        $.rexDialog('attach_upload').find('.attach_upload_form').rexSubmit(function(data){
            if (data !== false) {
                $.closeRexDialog('attach_upload');
                $.data(template, 'updateDatagrid')();
            }
        });
    });
{/literal}
{rexscript_stop}
</script>