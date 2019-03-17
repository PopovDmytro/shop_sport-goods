<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td class="one-line" colspan="4">
            <input class="filter" type="hidden" name="filter" value="" id="filter">
            <input class="filter" type="hidden" name="pcatalog" value="" id="pcatalog">

            <a class="filters" fl="" href="javascript: void(0);">Все</a>
            <a class="filters" fl="noimg" href="javascript: void(0);">Без картинок</a>
            <a class="filters" fl="noattr" href="javascript: void(0);">Без атрибутов</a>
            <a class="filters" fl="noactive" href="javascript: void(0);">Неактивные</a>
            <a class="filters active" fl="active" href="javascript: void(0);">Активные</a>
            <a class="filters" fl="top" href="javascript: void(0);">Лидер продаж</a>
            <a class="filters" fl="new" href="javascript: void(0);">Новинки</a>
            <a class="filters" fl="sale" href="javascript: void(0);">Акции</a>
        </td>
    </tr>
    <tr class="one-line-filters-fix">
        <td>
            <select id="pcatalog_filter">                
                <option value="">Категория не выбрана</option>
                {foreach from=$pcatalogList key=key item=item}
                    {if $item.id neq $pcatalog->id}
                        <option value="{$item.id}" {if $search_pcatalog and $search_pcatalog eq $item.id}selected{/if}>{if $item.level > 0}{section name=level loop=$item.level}-{/section}{/if}{$item.name}</option>
                    {/if}
                {/foreach}                
            </select> &nbsp;&nbsp;&nbsp;
        </td>
        <td>
            Search:&nbsp;
        </td>
        <td>
            <input class="filter search ui-state-default" id="product_list" type="text" value="" name="search" />
            <input type="hidden" class="filter" name="id" value="" id="product_id">
        </td>
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