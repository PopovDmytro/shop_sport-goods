222
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
            <td>No Records Found</td>
        </tr>
        </table>
    {/if}
{*** if isset records ***}
{else}
{if $columnSet.1}
  <table align="right" cellpadding="2" cellspacing="1" class="data_sort" border="0">
    <tr>
        <td width="80" align="right">Sort By:</td>
        {section name=col loop=$columnSet start=1}
          <!-- Check if the column is sortable -->
          {if $columnSet[col].link != ""}
              <td {$columnSet[col].attributes}>
              <a href="{$columnSet[col].link}">{$columnSet[col].label}</a>
             </td>
          {/if}
        {/section}
    </tr>
  </table>
  <br/>

{/if}

<table width="100%" cellpadding="2" cellspacing="1" class="data" border="0">
    <tr>
        <td valign="top">

        <table class="{if $flagLinesColored}Odd{/if}">
        {assign var  = columnNum value=1}
        <!-- Build body -->
        {section name=row loop=$recordSet}
          {math assign = count equation="y % (z/x)" x=$columns.count y=$smarty.section.row.iteration z=$recordLimit}

            <tr>
                  <td {$columns.attributes} width="">
                      {*section name=col loop=$recordSet[row]*}
                        {if $recordSet[row][0] != ''}{$recordSet[row][0]}{else}{$columnPreSet[0]->autoFillValue}{/if}<br/>
                    {*/section*}
                </td>
            </tr>

            {if $count == 0}
                {assign var = columnNum value=$columnNum+1}
                </table>
            </td>
            <td valign="top">
                <table class="{if $flagLinesColored}{if $columnNum is even}Even{else}Odd{/if}{else}Odd{/if}">
            {/if}
        {/section}
        </table>
        </td>
    </tr>
  </table>


  {if $flagPaged}
    <table width="100%" align="center" id="dg" border="0" cellpadding="2" cellspacing="0">
    {if $currentPage > $pagesNum}
        <tr>
            <td align='left'>
                Sorry, system does not serve more than {$pagesNum} pages. (You asked page {$currentPage})
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
             Showing records {$firstRecord} to {$lastRecord} from {$totalRecordsNum}{if $pagesNum > 1}, page {$currentPage} of {$pagesNum}{/if}
            </td>
        </tr>
    {/if}
       </table>
  {/if}
{/if}
{/strip}