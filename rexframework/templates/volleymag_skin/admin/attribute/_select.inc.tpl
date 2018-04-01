<div style="float: left; width: 90px; overflow: hidden;">
	{$attribute->name}
</div>

<div style="float: left;">
	<select name="attribute[{$attribute->id}]">
	
	{foreach from=$attributeList key=key item=subAttribute}
	
		<option value="{$subAttribute->id}" {if $attr2prod and $attr2prod->value eq $subAttribute->id}selected{/if}>{$subAttribute->name}</option>
	
	{/foreach}
	
	</select>
</div>
<div class="clear5"></div>