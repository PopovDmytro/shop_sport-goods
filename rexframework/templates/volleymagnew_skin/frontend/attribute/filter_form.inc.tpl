{if $filter_form}
    {if $filterNow.rangefrom and $filterNow.rangeto}
        <input type="hidden" name="filter[rangefrom]" value="{$filterNow.rangefrom}" id="rangefrom">
        <input type="hidden" name="filter[rangeto]" value="{$filterNow.rangeto}" id="rangeto">
    {/if}
    {$filter_form}
{/if}