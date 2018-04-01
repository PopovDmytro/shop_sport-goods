{strip}
<!-- Display search forms -->
{if $flagSearchForm}
<center>
<form method="POST">
    <table class="data_search" width="100%">
        <tr>
            <td></td>
            <td width="200"><input class="dinput" type="text" name="{$requestPrefix}query" value="{$search_last_value}" maxlength="128" /></td>
            <td width="100">
                <select class="dinput" name="{$requestPrefix}field">
                    {foreach from=$search_select item=type key=key}
                        <option value="{$key}" {if $key eq $search_selected}selected="selected"{/if}>{$type}</option>
                    {/foreach}
                </select>
            </td>
            <td width="100"><input class="dsubmit" type="submit" value="Search" /></td>
            <td></td>
        </tr>
    </table>
</form>
</center>
{/if}
<!-- Show paging links using the custom getPaging function -->
{*** if no records found ***}
{if $totalRecordsNum == 0}
    {if $flagShowNoRecords}
        <table width="100%" align="center" cellpadding="5" border="0" id="dg">
        <tr class="dgEven" valign = "middle">
            <td>No Records found</td>
        </tr>
        </table>
    {/if}
{*** if isset records ***}
{else}
<div id="datagrid_container">
<div class="table_wrapper">
<div class="table_hghg_inner">
  <table width="100%" cellpadding="0" cellspacing="0" class="data">
      <!-- Build header -->
      {if $flagHeader}
        <tr>
          {if $flagNumerable}
            <th class="ui-accordion-header ui-helper-reset" width="25">#</th>
            {/if}
          {section name=col loop=$columnSet}
              <th class="ui-accordion-header ui-helper-reset {if $smarty.section.col.last}last{/if}" {$columnSet[col].attributes}>
                  <!-- Check if the column is sortable -->
                  {if $columnSet[col].link != ""}
                     <a href="{$columnSet[col].link}">{$columnSet[col].label}</a>
                  {else}
                      {$columnSet[col].label}
                  {/if}
              </th>
          {/section}
        </tr>
      {/if}
        <!-- Build body -->
        {section name=row loop=$recordSet}

          <tr class="ui-state-default bg-reset {if $flagLinesColored}{if $smarty.section.row.iteration is even}Even op1{else}Odd op2{/if}{else}Odd{/if}">
              {if $flagNumerable}
                {math assign = index equation="y * x - x + z" x=$recordLimit y=$currentPage z=$smarty.section.row.iteration}
                <td align="right">{$index}</td>
              {/if}
              {section name=col loop=$recordSet[row]}
                <td {$columnSet[col].attributes} {if $smarty.section.col.last}class="last"{/if}>
                {if $recordSet[row][col] != ''}{$recordSet[row][col]}{else}{$columnPreSet[col]->autoFillValue}{/if}
                </td>
            {/section}
          </tr>
        {/section}
        {if $flagFillLines}
        {if $currentPage == $pagesNum }
            {math assign = diff equation="y * x - $totalRecordsNum" x=$recordLimit y=$currentPage z=$totalRecordsNum}
            {if $diff}
                {section name = empty loop=$diff start=0 step=1}

                      <tr class="Odd"><td colspan="{if $flagNumerable}{$columnsNum+1}{else}{$columnsNum}{/if}">&nbsp;</td></tr>

                {/section}
            {/if}
        {/if}
        {/if}
  </table>
  </div>
  </div>
  </div>

  {if $flagPaged}
    <table width="100%" align="center" id="dg" border="0" cellpadding="2" cellspacing="0">
    {if $currentPage > $pagesNum}
        <tr>
            <td align='left'>
                {$pagesNum} max
            </td>
        </tr>
    {else}
        {if $pagesNum > 1}
        <tr class="Page">
            <td align='left'>
              {getPaging prevImg="&laquo;  " nextImg="  &raquo;" separator=" | " delta="5" path=$PATH}
            </td>
        </tr>
        {/if}
        <tr>
            <td align='left'>
            {if $pager && $pager.current && $pager.pages && $pager.count && $pager.pagecount && $filters.inpage}
                {assign var=frompage value=$pager.current*$filters.inpage-$filters.inpage+1}
                {assign var=topage value=$frompage+$pager.pagecount-1}
                <div class="records">
                    Show Records from {$frompage} to {$topage} , total {$pager.count}{if $pager.pages > 1}, page {$pager.current} of {$pager.pages}{/if}
                </div>
            {else}
                <div class="records">
                    Show Records from {$firstRecord} to {$lastRecord} , total {$totalRecordsNum}{if $pagesNum > 1}, page {$currentPage} of {$pagesNum}{/if}
                </div>
            {/if}
            </td>
        </tr>
    {/if}
       </table>
  {/if}
{/if}
{/strip}