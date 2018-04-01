<h3 class="head1 ui-tabs-nav ui-helper-reset  ui-widget-header ui-corner-all">{if $act eq 'add'}Добавление товара{else}Редактирование товара № {$task}{/if} </h3>
{include file="product/add.header.tpl"}
{include file="product/add.inc.tpl"}
{include file="product/add.footer.tpl"}
<script>
    {rexscript_start}
    {literal}
    
        var product_id = 0;
        var sale = {/literal}{if $entity->sale}{$entity->sale}{else}0{/if}{literal};
        changeSale();
        
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
        
        template.find('select[name="entity[event]"]').die('change').live('change', function(){
            changeSale();
        });
            
        template.find('#button-step-done').die('click').live('click', function() {
            template.find('#productForm').rexSubmit(function(data){
                if (data != false) {
                    window.location.href = '/admin/?mod=product';
                }
            });
            return false;
        });
        
        function changeSale()
        {
            if (template.find('select[name="entity[event]"]').val() == 1) {
                template.find('select[name="entity[event]"]').parents('tr').append('<td>Скидка: <input type="text" name="entity[sale]" value="'+sale+'"/></td>');
            } else {
                template.find('input[name="entity[sale]"]').parents('td').remove();
            }
        }
    
    {/literal}
    {rexscript_stop}
</script>
