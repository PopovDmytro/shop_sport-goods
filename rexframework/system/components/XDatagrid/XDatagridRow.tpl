{strip}
    {section name=row loop=$recordSet}
        {section name=col loop=$recordSet[row]}
            <td {$columnSet[col].attributes} {if $smarty.section.col.last}class="last"{/if}>
                {if $recordSet[row][col] != ''}{$recordSet[row][col]}{else}{$columnPreSet[col]->autoFillValue}{/if}
            </td>
        {/section}
    {/section}
{/strip}