<h3 class="head1 ui-tabs-nav ui-helper-reset  ui-widget-header ui-corner-all">{if $act eq 'add'}Добавление товара{else}Редактирование товара № {$task}{/if} </h3>
{include file="product/add.header.tpl"}
{include file="product/add.inc.tpl"}
{include file="product/add.footer.tpl"}
<script>
    {rexscript_start}
    {literal}
    
        var product_id = 0;

        if (template.find('#product-id-add').length) {
            product_id = template.find('#product-id-add').val();  
        }

        template.find('#button-step-next').die('click').live('click', function() {
            template.find('#productForm').rexSubmit(function(data){
                if (data != false) {
                    window.location.href = '/admin/?mod=attr2Prod&product_id='+data;
                }
            });
            return false;
        });

        template.find('#button-step-done').die('click').live('click', function() {
            template.find('#productForm').rexSubmit(function(data){
                if (data != false) {
                    window.location.href = '/admin/?mod=product';
                }
            });
            return false;
        });

    {/literal}
    {rexscript_stop}
</script>
{include "_block/field_autosave.inc.tpl"}
