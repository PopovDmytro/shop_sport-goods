{if $values}
	<li data-accordion-item class="accordion-item">
		<a href="javascript:void(0)" class="accordion-title">{$attribute->name}</a>
		<div data-tab-content class="accordion-content">
			<ul class="categories_brands-selector no-bullet filter_choose_all_list">
				<li>
					<div class="radio-holder">
						<label>
							<input class="filter_choose_all" type="checkbox" id="brand_check_all" checked="checked">
							<span class="checkbox-trigger checkbox-trigger--grey"></span>
							Выбрать все
						</label>
					</div>
				</li>
                {foreach from=$values_sorted key=key item=value}
                    {assign var=attr_index value=$value->id}
                    {assign var=attr_index value="`$attribute->id`_`$attr_index`"}
                    {assign var=attr_index value=$attr_index|md5}
					<li>
						<div class="radio-holder">
							<label>
								<input type="checkbox" name="filter[attribute][{$attribute->id}][]" value="{$value->id}" {if !$checked_list or $checked_list and $checked_list.$attr_index}checked{/if}>
								<span class="checkbox-trigger checkbox-trigger--grey"></span>
								{$value->name}
							</label>
						</div>
					</li>
                {/foreach}
			</ul>
		</div>
	</li>
{/if}

