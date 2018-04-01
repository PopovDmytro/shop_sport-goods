{if $attr2prod}
<tr>
	<td>
	{$attribute->name}
	</td>
	<td>
	{if $attribute->filtered and $pcatalog}
		<a href="http://{$DOMAIN}/filter-catalog/{$pcatalog->alias}/{$attribute->alias}/{if $attr2prod->value}1{else}0{/if}/">{if $attr2prod->value}Да{else}Нет{/if}</a>
	{else}
		{if $attr2prod->value}Да{else}Нет{/if}
	{/if}	
	</td>
</tr>
{/if}