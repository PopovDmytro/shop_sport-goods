{if $values}
	<li ><strong class="toggle-btn">{$attribute->name}</strong>
	<ul class="filter_choose_all_list">
		<li class="checker-all-item">
			<label>
				<input type="checkbox" class="filter_choose_all" checked="checked">
				Выбрать все
			</label>
		</li>
	{foreach from=$values_sorted key=key item=value}
		{assign var=attr_index value=$value->id}
		{assign var=attr_index value="`$attribute->id`_`$attr_index`"}
		{assign var=attr_index value=$attr_index|md5}
		<li {*if !$values[$value->id] and $filter_reduce}class="attr-not-show"{/if*}>
			<label>
				<input type="checkbox" name="filter[attribute][{$attribute->id}][]" value="{$value->id}" {*if !$values[$value->id] and $filter_reduce}disabled="disabled"{/if*} {if !$checked_list or $checked_list and $checked_list.$attr_index}checked{/if}>
				{$value->name}
			</label>
		</li>
	{/foreach}
	</ul>
	</li>
{/if}