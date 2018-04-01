{if $attr2prodList}
<tr>
	<td class="first">
	{$attribute->name}
	</td>
	
	{foreach from=$attr2prodList item=attr2prod}
	<td>
	{if $attribute->filtered and $pcatalog}
		<a href="{url mod=pCatalog act=byattribute task=$pcatalog->alias attribute=$attribute->alias value=$attr2prod->value }">{$attr2prod->value}</a>
	{else}
		{$attr2prod->value}
	{/if}                                   
	</td>
	{/foreach}
</tr>
{/if}