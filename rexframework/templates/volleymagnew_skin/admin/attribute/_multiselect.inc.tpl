<div style="float: left; width: 90px; overflow: hidden;">
    <div style="margin: 34px 5px;">{$attribute->name}</div>
</div>
    
<div style="float: left;width: calc(100% - 92px);">
    <select style="width: 160px;" size="4" class="multiselect" multiple="multiple" data-related-field="attribute_id" data-related-id="{$attribute->id}" data-changed-name="value" data-entity="attr2Prod" name="attribute[{$attribute->id}][]">
    {*<select multiple name="attribute[{$attribute->id}][]">*}
    
    {foreach from=$attributeList key=key item=subAttribute}
    
        {assign var=attr_id value=$subAttribute->id}
    
        {if $attr2prod and $attr2prod.$attr_id}
            <option title="{$subAttribute->name}" value="{$subAttribute->id}" selected>{$subAttribute->name}</option>
        {else}
            <option title="{$subAttribute->name}" value="{$subAttribute->id}">{$subAttribute->name}</option>
        {/if}

    {/foreach}
    
    </select>
</div>
<div class="clear5"></div>

<script type="text/javascript">
{rexscript_start}
{literal}
    $(document).ready(function() {
        template.find('.multiselect').multiselect({
            dividerLocation: 0.5,
            selected: function(event, ui) {
                sendRelatedAutoSave($(ui.option));
            },
            deselected: function(event, ui) {
                sendRelatedAutoSave($(ui.option), false, 'remove');
            }
        });
    });
{/literal}
{rexscript_stop}
</script>
{include "_block/field_autosave.inc.tpl"}
<style type="text/css">
{rexstyle_start}
   .ui-multiselect {
       width: 100% !important;
       height: 350px!important;
   }
   .ui-multiselect div.selected {
       width: 50% !important;
       height: 350px!important;
   }
   .ui-multiselect div.selected div.ui-widget-header {
       height: 32px!important;
   }
   .ui-multiselect div.selected ul.selected, .ui-multiselect div.available ul.available {
       height: 317px!important;
   }
   .ui-multiselect div.available {
       width: calc(50% - 1px) !important;
       height: 350px!important;
   }
   #cke_contents_DataFCKeditor {
        height: 300px!important;
    }
{rexstyle_stop}
</style>