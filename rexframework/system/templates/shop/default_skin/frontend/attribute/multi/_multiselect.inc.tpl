{if $attr2prodList}
<tr>
	<td class="first">
	{$attribute->name}
	</td>
	
	{foreach from=$attr2prodList item=attr2prod}
	<td>
        <div class="td-comp">
		    {if $attr2prod}
		    {foreach from=$attributeList key=key item=subAttribute}	
			    
			    {assign var=attr_id value=$subAttribute->id}
			    
			    {if $attr2prod.$attr_id}
				    {if $attribute->filtered and $pcatalog}
					    <a href="{url mod=pCatalog act=byattribute task=$pcatalog->alias attribute=$attribute->alias value=$subAttribute->id }">{$subAttribute->name}</a>
				    {else}
					    {$subAttribute->name}
				    {/if}
			    {/if}
		    {/foreach}
		    {/if}
        </div>
	</td>
	{/foreach}
</tr>
{/if}