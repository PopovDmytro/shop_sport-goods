<div class="main1 ui-widget-content ui-corner-all ui-state-default bg-reset">
    <table class="general_form" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td colspan="2">
                    <h3 class="head1 ui-tabs-nav ui-helper-reset ui-widget-header ui-corner-all"> Настройки Сайта </h3>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px 0px;">
                    <form action="index.php?mod=config" name="selectGroup">
                    <input type="hidden" name="mod" value="{$mod}">
                    <select name="task" onchange="document.forms['selectGroup'].submit();">
                        {foreach from=$settings_sections item=settings_section name=ss}
                            {if !$smarty.foreach.ss.last}
                                <option value="{$smarty.foreach.ss.index+1}" {if $task eq $smarty.foreach.ss.index + 1}selected="selected"{/if}>{$settings_section}</option>
                            {/if}
                        {/foreach}
                    </select>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="datagrid_container">
        <form class="addForm" action="" method="POST">
            <input type="hidden" name="task" value="{if $task}{$task}{else}1{/if}">
            {$dg}
            <input class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" style="margin: 5px 0 10px 15px;" type="submit" name="submit" value="Save">
            </form>
    </div>
</div>