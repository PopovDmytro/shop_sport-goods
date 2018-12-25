{if $attr2prodList}
	{foreach from=$attr2prodList item=attr2prod}
        <div class="compare_product-block_colors compare_product-info">
		    {if $attr2prod}
		    {foreach from=$attributeList key=key item=subAttribute}
			    {assign var=attr_id value=$subAttribute->id}

			    {if $attr2prod.$attr_id}
					{$subAttribute->name}
			    {/if}
		    {/foreach}
		    {/if}
        </div>
	{/foreach}
{/if}