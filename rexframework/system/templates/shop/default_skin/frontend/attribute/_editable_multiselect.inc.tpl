<tr>
	<td>
	{$attribute->name}
	</td>
	<td>
	
	<select name="cart[{$attribute->id}]">
	{assign var=flag value=false}
    
	{foreach from=$attributeList key=key item=subAttribute}
	
		{assign var=attr_id value=$subAttribute->id}
	
		{if $attr2prod and $attr2prod.$attr_id}
			<option value="{$subAttribute->id}">{$subAttribute->name}</option>
            {assign var=flag value=true}
		{/if}

	{/foreach}
	    
    {if !$flag}
        <option value="0">на назначено</option>
    {/if}
    
	</select>
	
	</td>
</tr>