{if $attr2prodList}
<tr>
	<td class="first">
	{$attribute->name}
	</td>
	
	{foreach from=$attr2prodList item=attr2prod}
	<td>
		{if $attr2prod}
		{foreach from=$attributeList key=key item=subAttribute}	
			{if $attr2prod and $attr2prod->value eq $subAttribute->id}
				{if $attribute->filtered and $pcatalog}
					<a href="{url mod=pCatalog act=byattribute task=$pcatalog->alias attribute=$attribute->alias value=$subAttribute->id }">{$subAttribute->name}</a>
				{else}
					{$subAttribute->name}
				{/if}
			{/if}
		{/foreach}
		{/if}
	</td>
	{/foreach}
</tr>
{/if}