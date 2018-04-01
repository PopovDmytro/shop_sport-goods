{include file="parent/default.tpl"}
<script type="text/javascript">
{rexscript_start}
{literal}
    var clearFilters = function() {
        template.find('#pcatalog').val('');
        template.find('#filter').val('');
        template.find('.search').val('');
        template.find('.datefrom').val('');
        template.find('.dateto').val('');
        template.find('.check').val([]).trigger("liszt:updated");
        template.find('#pcatalog_filter option').each(function() {
            $(this).removeAttr('selected');
        });
        template.find('#pcatalog_filter option:first').attr('selected', 'selected');
        $.data(template, 'updateDatagrid')();
    }
    $.data(template, 'clearFilters', clearFilters);
    
    template.find('.datagrid_container').bind('updateDatagrid', function () {
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('.filters').die('click').live('click', function() {
        var fl = $(this).attr('fl');
        
        template.find('#filter').val(fl);
        $.data(template, 'updateDatagrid')();
    });
    
    template.on('change', '#pcatalog_filter', function() {
        var value = $(this).val();
        
        template.find('#pcatalog').val(value);
        $.data(template, 'updateDatagrid')();
    });
    
    template.find('.images').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('pImage', false, {product_id: product_id, in_parent: true}, 'images', 'Images');
    });
    
    template.find('.attributes').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('attr2Prod', false, {product_id: product_id, in_parent: true}, 'attributes', 'Attributes');
    });
    
    template.find('.skus').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('sku', false, {product_id: product_id, in_parent: true}, 'skus', 'Skus');
    });
    
    template.find('.attach').die('click').live('click', function() {
        var product_id = $(this).attr('product_id');
        $.showRexDialog('attach', false, {product_id: product_id, in_parent: true}, 'attach', 'Attaches for product '+product_id);
    });
     
    $.rexDialog('attributes').find('.item_save').die('click').live('click', function(){
        $.rexDialog('attributes').find('.addform').rexSubmit(function(data){
            if (data !== false) {
                $.closeRexDialog('attributes');
            }
        });
    });
{/literal}
{rexscript_stop}
</script>