{if $attr2prod}
    {if $attribute->name neq 'Пол'}
    <tr>
	    <td>
	    {$attribute->name}
	    </td>
	    <td>
	    
	    {foreach from=$attributeList key=key item=subAttribute}
	    
		    {assign var=attr_id value=$subAttribute->id}
	    
		    {if $attr2prod.$attr_id}
			    {if $attribute->filtered and $pcatalog}         
				    <a href="{url mod=pCatalog task=$pcatalog->alias}filter/?filter[attribute][{$attribute->id}][]={$subAttribute->id}">{$subAttribute->name}</a>
			    {else}
				    {$subAttribute->name}
			    {/if}
		    {/if}

	    {/foreach}
	    
	    </td>
    </tr>
    {/if}
{/if}