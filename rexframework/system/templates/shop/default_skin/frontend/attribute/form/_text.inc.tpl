{if $values}
	<li><strong>{$attribute->name}</strong><br/>
	<ul>
	{foreach from=$values key=key item=value}
		{assign var=attr_index value="`$attribute->id`_`$value.value`"}
		{assign var=attr_index value=$attr_index|md5}
		<li {if !$value.show and $filter_reduce}class="attr-not-show"{/if}><input type="checkbox" name="filter[attribute][{$attribute->id}][]" value="{$value.value}" {if !$value.show and $filter_reduce}disabled="disabled"{/if} {if $checked_list and $checked_list.$attr_index}checked{/if}>{$value.value}</li>
	{/foreach}
	</ul>
	</li>
{/if}