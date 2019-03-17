{if $templ eq 'user'}
<div class="input-holder column fillials_rt">
	{*<b class="table-reg-ag-l">Отделение Новой Почты:</b>*}
	<select name="profile[fillials]" class="select_default titlex">
		<option id="Selcity" value="0">Выберите отделение Новой Почты города</option>
		{foreach from=$fillials item=ifillials}
			<option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
		{/foreach}
	</select>
</div>
{else if $templ eq 'registration'}
<div class="input-holder column fillials_rt">
	{*<b class="table-reg-ag-l">Отделение Новой Почты:</b>*}
	<select name="registration[fillials]" class="select_default titlex">
		<option id="Selcity" value="0">Выберите отделение Новой Почты города</option>
		{foreach from=$fillials item=ifillials}
			<option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
		{/foreach}
	</select>
</div>
{else if $templ eq 'cart'}
<div class="input-holder column fillials_rt">
    {*<div><b>Адрес отделения транспортной компании:</b></div>*}
	<select name="order[fillials]" class="select_default titlex">
		<option id="Selcity" value="0">Адрес отделения транспортной компании</option>
		{foreach from=$fillials item=ifillials}
			<option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
		{/foreach}
	</select>
</div>
{/if}