<table cellspacing="0" cellpadding="0" border="0">
    <tr>
        {include file="_block/filters.tpl"}
        <td>
            Статус:&nbsp;
        </td>
        <td>
            <select name="status" class="filter">
                <option value="13"{if $order_status eq 13} selected="selected"{/if}>Все</option>
                <option value="1"{if $order_status eq 1} selected="selected"{/if}>Новые</option>
                <option value="4"{if $order_status eq 4} selected="selected"{/if}>Оплачивается</option>
                <option value="2"{if $order_status eq 2} selected="selected"{/if}>Оплаченные</option>
                <option value="5"{if $order_status eq 5} selected="selected"{/if}>Доставляется</option>
                <option value="7"{if $order_status eq 7} selected="selected"{/if}>Доставлены</option>
                <option value="3"{if $order_status eq 3} selected="selected"{/if}>Завершенные</option>
                <option value="6"{if $order_status eq 6} selected="selected"{/if}>Отменены</option>
                <option value="8"{if $order_status eq 8} selected="selected"{/if}>Обрабатываются</option>
                <option value="9"{if $order_status eq 9} selected="selected"{/if}>СРОЧНО</option>
                <option value="10"{if $order_status eq 10} selected="selected"{/if}>Возврат</option>
                <option value="11"{if $order_status eq 11} selected="selected"{/if}>Обмен</option>
                <option value="12"{if $order_status eq 12} selected="selected"{/if}>Все кроме завершенных и отмененных</option>
            </select>
            &nbsp;
        </td>
        <td>
            Search:&nbsp;
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
        <!--td>
            <span id="order_amount" style="margin-left:20px;">Заказов: {$statistic.orders}</span>
            <span id="order_total" style="margin-left:20px;">Всего на: {$statistic.total} грн</span>
            {if $statistic.total-$statistic.zakup >= 0}<span id="order_profit" style="margin-left:20px;">Прибыль: {$statistic.total-$statistic.zakup} грн</span>
            {else}<span id="order_profit" style="margin-left:20px;color:red;" title="Необходимо повысить цену или снизить размер скидок">Убыток: {$statistic.total-$statistic.zakup} грн</span>
            {/if}
        </td-->
        <!--<td>
            Оборот: {$totalPrice} грн
        </td>-->
        <td>
            <ul id="icons" class="ui-widget ui-helper-clearfix" style="margin-left:20px;">
                <li class="ui-state-default ui-corner-all" title="Создать новый заказ" style="padding-right:10px">
                    <a href="/admin/?mod=order&act=add">
                        <span class="ui-icon ui-icon-plusthick"></span>Создать заказ
                    </a>
                </li>
            </ul>
        </td>
    </tr>
    <tr>
        <td class="percent_summa" style="color: green;">

        </td>
    </tr>
</table>