

        <div class="holder seleceted-holder">
            {*<h2>Выбранные фильтры</h2> *}
            <form id="filter-form-selected" action="{url route=shop_fsearch task=$pcatalog->alias uri=''}" method="get">
                {if $filterSelected}
                <ul class="filter-menu-selected">
                    {foreach from=$filterSelected key=key item=filterOne}
                        <li>
                            <span>{$filterOne.name}</span>
                            <br />
                            {if $key|strstr:'attr_'}
                                {if $filterOne.val_min and $filterOne.val_max}
                                    {$filterOne.val_min} - {$filterOne.val_max} {$filterOne.val_name}<div class="filter-delete" id="filter_{$key|substr:5}">x</div>    
                                {else}
                                    <ul>
                                        {foreach from=$filterOne key=k item=attrValue}
                                            {if $k neq 'name'}
                                                <li>
                                                    {$attrValue} <div class="filter-delete" id="{$key|substr:5}x{$k|substr:4}">x</div>
                                                </li>
                                            {/if}    
                                        {/foreach}
                                    </ul>
                                {/if}
                            {elseif $key eq 'brand'}
                                <ul>
                                    {foreach from=$filterOne.value key=brandID item=brandName}
                                        <li>
                                            {$brandName} <div class="filter-delete brand-filter" id="filter_{$brandID}">x</div>
                                        </li>
                                    {/foreach}
                                </ul>
                            {else}
                                <ul>
                                    <li>
                                        {$filterOne.value} <div class="filter-delete" id="filter_{$key}">x</div>
                                    </li>
                                </ul>
                            {/if}
                        </li>
                    {/foreach}
                </ul>
                {/if}
                <center><input type="submit" id="free_button" name="clearfilter" value="Сброс" class="a-button">
                        <input type="submit"  name="search" value="Поиск" class="a-button">
                </center>
            </form>
        </div>


<script language="javascript">
    {literal}
        $('.filter-delete').unbind('click').bind('click', function(){
            $(this).unbind('click');
            var IDfilter = $(this).attr('id');
            if (IDfilter.match(/(\d+)x([0-9a-zA-Z_.]+)/)) {
                var matched = IDfilter.match(/(\d+)x([0-9a-zA-Z_.]+)/);
                console.log(matched);
                $('#filter-form input[name="filter[attribute]['+matched[1]+'][]"][value="'+matched[2]+'"]').removeAttr('name');
            } else if (IDfilter.match(/filter_(\w+)/)) {
                var matched = IDfilter.match(/filter_(\w+)/);
                if (matched[1] == 'price') {
                    $('#filter-form input[name="filter[rangefrom]"]').removeAttr('name');    
                    $('#filter-form input[name="filter[rangeto]"]').removeAttr('name');    
                } else if ($(this).hasClass('brand-filter')) {
                    $('#filter-form #brand_'+matched[1]).removeAttr('name');    
                } else {
                    $('#filter-form input[name*="filter[attribute]['+matched[1]+'"]').removeAttr('name');
                }
            }
            $('#filter-form').submit();
        })
    {/literal}
</script>