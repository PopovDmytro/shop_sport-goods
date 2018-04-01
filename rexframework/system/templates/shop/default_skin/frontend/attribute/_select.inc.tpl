<tr>
	<td>
	{$attribute->name}
	</td>
	<td>
	{foreach from=$attributeList key=key item=subAttribute}	
		{if $attr2prod and $attr2prod->value eq $subAttribute->id}
			{if $attribute->filtered and $pcatalog}
				<a href="{url mod=pCatalog task=$pcatalog->alias}filter/?filter[attribute][{$attribute->id}][]={$subAttribute->id}">{$subAttribute->name}</a>
			{else}
				{$subAttribute->name}
			{/if}
		{/if}
	{/foreach}
	</td>
</tr>