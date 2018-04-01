<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>
            Search:&nbsp;
        </td>
        <td>
            <input name="search" class="filter search ui-state-default" type="text" value="" autocomplete="off" />
        </td>
        {if $colorAttr}
            <td style="padding:0px 5px;">
                 Атрибуты :
                 <select class="filter-attr">
                    <option value="0">Нет</option>
                    {foreach from=$colorAttr key=key item=attribute}
                        <option value="{$attribute.id}">{$attribute.name}</option>
                    {/foreach}
                </select>
            </td>
        {/if}
        <td>
            <ul id="icons" class="ui-widget ui-helper-clearfix">
                <li class="ui-state-default ui-corner-all" title="Search">
                    <a class="searchexec" href="javascript: void(0);">
                        <span class="ui-icon ui-icon-search button_search"></span>
                    </a>
                </li>
                <li class="ui-state-default ui-corner-all" title="Refresh (clear filters)">
                    <a class="searchreset" href="javascript: void(0);">
                        <span class="ui-icon ui-icon-refresh button_reset"></span>
                    </a>
                </li>
            </ul>

        </td>
    </tr>
</table>
<input class="filter" name="product_id" type="hidden" value="{$product_id}" id="product_id">
<script>
    {literal}

        $('.filter-attr').change(function(){
            $.rex('attr2Prod', 'filterAttribute', {attr_id: $(this).val(), product_id: {/literal}{$product_id}{literal}}, function(data) {
                $('#ajax-select-attribute').remove();
                if (data != false) {
                    var block = '<td style="padding:0px 5px;" id="ajax-select-attribute">';
                    block += 'Значения :';
                    block += '<select class="filter" name="attribute_id">';
                    block += '<option value="0">Нет</option>';
                    for (var i in data) {
                        block += '<option value="'+data[i].id+'">'+data[i].child_name+'</option>';    
                    }
                    block += '</select>';
                    block += '</td>';
                    $('.filter-attr').parent().after(block);
                }
            });
        });    

    {/literal}
</script>
