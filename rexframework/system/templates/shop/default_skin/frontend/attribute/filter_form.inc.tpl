{if $filter_form}

    <!--<p class="sidebar-p">Параметры товара</p>-->	
	<ul class="filter-sub">
        {*if $brandName}
            <input type="hidden" name="filter[brand]" value="{$brandName}">
        {/if*}
        <li>
            {if $filterNow.rangefrom and $filterNow.rangeto}
                <input type="hidden" name="filter[rangefrom]" value="{$filterNow.rangefrom}" id="rangefrom">
                <input type="hidden" name="filter[rangeto]" value="{$filterNow.rangeto}" id="rangeto"> 
            {/if}
	        {$filter_form}
        </li>
	</ul>

{/if}