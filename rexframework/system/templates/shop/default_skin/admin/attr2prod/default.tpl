{if !$in_parent}
    <h3 class="head1 ui-tabs-nav ui-helper-reset ui-widget-header ui-corner-all">Добавление атрибутов товару № {$product->id}</h3>
    {include file="product/add.header.tpl"}
{/if}
<div style="overflow-y: auto">
    {if $form}
        <form action="" enctype="multipart/form-data" method="post" id="attrprodForm" class="addform">
            <input type="hidden" name="mod" value="{$mod}">
            <input type="hidden" name="act" value="{$act}">
            <input type="hidden" name="task" value="{$task}">
            <input type="hidden" name="product_id" value="{$product->id}">
            <input type="hidden" name="attribute[submit]" value="1">

            <div class="popup-all-path" style="padding-top: 10px;">
                {$form}
            </div>
        </form>
    {else}
        <p align="center">No Records Found</p>
    {/if}
</div>
{if $in_parent and $form}
<table class="frame-t-s" cellspacing="5" cellpadding="0" border="0" align="center">
    <tr valign="">
        <td class="frame-t-td-s" colspan="2"><button id="button" type="button" class="item_save ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" name="attribute[submit]" ><span class="ui-button-text">Save</span></button></td>
    </tr>
</table>
{elseif !$in_parent}
    {include file="product/add.footer.tpl"}
    <script>
        {rexscript_start}
        {literal}
        
            template.find('#button-step-next').die('click').live('click', function() {
                if (template.find('#attrprodForm').length) {
                    template.find('#attrprodForm').rexSubmit(function(data){
                        if (data != false) {
                            window.location.href = template.find('#button-step-next').parents('a').attr('href');
                        }
                    });
                } else {
                    window.location.href = template.find('#button-step-next').parents('a').attr('href');    
                }
                return false;
            });    
        
        {/literal}
        {rexscript_stop}
    </script>
{/if}