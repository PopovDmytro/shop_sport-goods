<div style="float: left; width: 90px; overflow: hidden;">
    <div style="margin: 34px 5px;">{$attribute->name}</div>
</div>
	
<div style="float: left;">
    <select style="width: 160px;" size="4" class="multiselect" multiple="multiple" name="attribute[{$attribute->id}][]">
	{*<select multiple name="attribute[{$attribute->id}][]">*}
	
	{foreach from=$attributeList key=key item=subAttribute}
	
		{assign var=attr_id value=$subAttribute->id}
	
		{if $attr2prod and $attr2prod.$attr_id}
			<option title="{$subAttribute->name}" value="{$subAttribute->id}" selected>{$subAttribute->name|truncate:20:"..."}</option>
		{else}
			<option title="{$subAttribute->name}" value="{$subAttribute->id}">{$subAttribute->name|truncate:20:"..."}</option>
		{/if}

	{/foreach}
	
	</select>
</div>
<div class="clear5"></div>

<script type="text/javascript">
{rexscript_start}
{literal}
    template.find('.multiselect').multiselect();

{/literal}
{rexscript_stop}
</script>
<style type="text/css">
{rexstyle_start}
   .ui-multiselect {
       height: 96px!important;
   }
   .ui-multiselect div.selected {
       height: 95px!important;
   }
   .ui-multiselect div.selected div.ui-widget-header {
       height: 32px!important;
   }
   .ui-multiselect div.selected ul.selected, .ui-multiselect div.available ul.available {
       height: 63px!important;
   }
   .ui-multiselect div.available {
       height: 96px!important;
   }
   #cke_contents_DataFCKeditor {
        height: 300px!important;
    }
{rexstyle_stop}
</style>