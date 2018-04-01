<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td>
            Search:&nbsp;
        </td>
        <td>
            <input class="filter search ui-state-default" type="text" value="" autocomplete="off" name="search" />
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
    <tr>
        <td colspan="3">
            <input class="filter" name="pid" type="hidden" value="{$pid}" id="pid">
            {if $parent_attribute}
                <br /><br />
                Подкатегория <b>{$parent_attribute->name}</b>. <u><a href="{url mod=attribute pid=$parent_attribute->pid}">Перейти на уровень выше</a></u>
            {/if}
        </td>
    </tr>
</table>