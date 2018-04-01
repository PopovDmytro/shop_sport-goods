{if $templ eq 'user'}
<tr class="fillials_rt">
	<td class="table-reg-ag-l">Отделение Новой Почты:</td>
	<td class="table-reg-ag-r">
	    <select name="profile[fillials]" style="width: 260px" class="select_default titlex">
	        <option id="Selcity" value="0">Выберите отделение Новой Почты города</option>
	        {foreach from=$fillials item=ifillials}
	            <option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
	        {/foreach}
	    </select>
	</td>
</tr>
{else if $templ eq 'registration'}
<tr class="fillials_rt">
	<td class="table-reg-ag-l">Отделение Новой Почты:</td>
	<td class="table-reg-ag-r">
	    <select name="registration[fillials]" style="width: 260px" class="select_default titlex">
	        <option id="Selcity" value="0">Выберите отделение Новой Почты города</option>
	        {foreach from=$fillials item=ifillials}
	            <option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
	        {/foreach}
	    </select>
	</td>
</tr>
{else if $templ eq 'cart'}
<tr class="fillials_rt">
    <td class="cart-attr-l">
    <div><b>Адрес отделения транспортной компании:</b></div>
        <select name="order[fillials]" style="width: 260px" class="select_default titlex">
            <option id="Selcity" value="0">Адрес отделения транспортной компании</option>
            {foreach from=$fillials item=ifillials}
                <option value="{$ifillials.name}" cid="{$ifillials.city_id}" {if $user.fillials eq $ifillials.name}selected{/if}>{$ifillials.name}</option>
            {/foreach}
        </select>
    </td>
</tr>
{/if}