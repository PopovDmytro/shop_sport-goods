<tr>
    <td>
    {$attribute->name}
    </td>
    <td>
    {if $rangefrom eq $rangeto}
        {$rangefrom} {$rangename}
    {else}
        {$rangefrom} - {$rangeto} {$rangename}
    {/if}
    </td>
</tr>