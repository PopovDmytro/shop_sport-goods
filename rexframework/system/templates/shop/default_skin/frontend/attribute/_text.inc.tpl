{if $attr2prod}
<tr>
	<td>
	{$attribute->name}
	</td>
	<td>
	{if $attribute->filtered and $pcatalog}
		<a href="{url mod=pCatalog task=$pcatalog->alias}filter/?filter[attribute][{$attribute->id}][]={$attr2prod->value}"{*http://{$DOMAIN}/filter-catalog/{$pcatalog->alias}/{$attribute->alias}/{$attr2prod->value}/*}">{$attr2prod->value}</a>
	{else}
		{$attr2prod->value}
	{/if}
	</td>
</tr>
{/if}