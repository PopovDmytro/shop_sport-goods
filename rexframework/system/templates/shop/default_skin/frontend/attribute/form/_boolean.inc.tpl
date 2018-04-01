{if $values}
	<li><strong>{$attribute->name}</strong><br/>
	<ul>
	{assign var=isset value=0}
	{foreach from=$values key=key item=value}
		{assign var=attr_index value="`$attribute->id`_`$value.value`"}
		{assign var=attr_index value=$attr_index|md5}
		<li {*if !$value.show}class="attr-not-show"{/if*}><input type="radio" name="filter[attribute][{$attribute->id}][]" value="{if $value.value}1{else}0{/if}" {*if !$value.show}disabled="disabled"{/if*} {if $checked_list and $checked_list.$attr_index}checked{assign var=isset value=1}{/if}>{if $value.value}Да{else}Нет{/if}</li>
	{/foreach}
		{*<li><input type="radio" name="filter[attribute][{$attribute->id}][]" value="3" {if !$isset}checked{/if}>Не Важно</li>*}
	</ul>
	</li>
{/if}