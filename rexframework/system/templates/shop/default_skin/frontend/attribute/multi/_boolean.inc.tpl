{if $attr2prodList}
<tr>
	<td class="first">
	{$attribute->name}
	</td>
	
	{foreach from=$attr2prodList item=attr2prod}
	<td>
	{if $attribute->filtered and $pcatalog}
        {if $attr2prod->value}{assign var=attr_value value=1}{else}{assign var=attr_value value=0}{/if}
		<a href="{url mod=pCatalog act=byattribute task=$pcatalog->alias attribute=$attribute->alias value=$attr_value}">{if $attr2prod->value}Да{else}Нет{/if}</a>
	{else}
		{if $attr2prod->value}Да{else}Нет{/if}
	{/if}
	</td>
	{/foreach}
</tr>
{/if}