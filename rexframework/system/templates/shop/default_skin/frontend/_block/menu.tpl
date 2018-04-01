<div class="sidebarleft-box-title round">
    <h2>Категории</h2>
</div>
{include file="pcatalog/menu.inc.tpl"}
{if $filterSelected}
    {include file="attribute/selected.tpl"}
{/if} 
<form id="filter-form" action="{url route=shop_fsearch task=$pcatalog->alias uri=''}" name="filter-form" method="get">
    {include file="brand/list.inc.tpl"}
    {if $filter_form}
        <div class="sideRight-box-title round">
            <div class="lmenu-text-title">Параметры товара</div>  
            <div class="dotted mt5"></div>
        </div>
        <div class="sideRight-box-conttent">   
            {include file="attribute/filter_form.inc.tpl"}
        </div>
    {/if}
    {if $pricerange}
        <div class="sideRight-box-title round">
            <div class="lmenu-text-title">Цена</div>
        </div>
        <div class="sideRight-box-conttent">
            {include file="pcatalog/filter_price.inc.tpl"}
        </div>
    {else}
        <div class="sideRight-box-title round">
            <div class="lmenu-text-title"><a href="{url mod=news act=archive}"> Новости </a></div>
        </div>
        <div class="sideRight-box-conttent">
        <div class="dotted"></div>
            {include file="news/list.tpl"}
        </div>
    {/if}
    {if $filter_form and !$instant_filter}
        <center><input type="submit" value="Подобрать" class="a-button"></center>
    {else}
        <script language="javascript">
            {literal}
                $('#filter-form input:not([type=submit])').unbind('change').bind('change', function(event){
                    $('#filter-form').submit();
                });
            {/literal}
        </script>
    {/if}
</form>
