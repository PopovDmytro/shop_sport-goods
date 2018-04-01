{if $values_sorted}
	<li><strong>{$attribute->name}</strong><br/>
	<ul>
	{*foreach from=$values key=key item=value}
		{assign var=attr_index value="`$attribute->id`_`$key`"}
		{assign var=attr_index value=$attr_index|md5}
		<li><input type="checkbox" name="filter[{$attribute->id}][]" value="{$key}" {if $checked_list and $checked_list.$attr_index}checked{/if}>{$value}</li>
	{/foreach*}
	
	{foreach from=$values_sorted key=key item=value}   
		{assign var=attr_index value=$value->id}
		{assign var=attr_index value="`$attribute->id`_`$attr_index`"}
		{assign var=attr_index value=$attr_index|md5}
		<li {if !$values[$value->id] and $filter_reduce}class="attr-not-show"{/if}><input type="checkbox" name="filter[attribute][{$attribute->id}][]" value="{$value->id}" {if !$values[$value->id] and $filter_reduce}disabled="disabled"{/if} {if $checked_list and $checked_list.$attr_index}checked{/if}>{$value->name}</li>
	{/foreach}
	
	</ul>
	</li>
{/if}