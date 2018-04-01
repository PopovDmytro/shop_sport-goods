<div class="category">
    {if $navCategoryList}
    <div class="nav_category round">
        <p class="navigation-p">                                      
            {strip}
            {foreach from=$navCategoryList key=key item=category name=navBar}
            <a href="{url mod=pCatalog act=default task=$category.alias}">{$category.name}</a>
            {if !$smarty.foreach.navBar.last}&nbsp;->&nbsp;{/if}
            {/foreach}
            {/strip}
        </p>
    </div>
    {/if}
<div id="content">
    {include file="pcatalog/product.list.inc.tpl"}
    
    {if "ajax_paging"|settings == "true"}
        <div class="ajax-paging">Загрузить ещё {"per_page"|settings} товаров</div>
    {/if}
    <div class="clear"></div>
    {if $pager->count neq 1}
     <div class="pagination round" align="center" style="visibility: visible;">
    
        {foreach from=$pager->pages key=key item=item}
            {if $pager->currentPage eq $item}
            <div class="pagination_div">
                <b>{$item}</b>
            </div>
            {elseif $item eq 1}
                <div class="pagin-item"><a href="{url mod=pCatalog act=default task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></div>
            {else}
                <div class="pagin-item"><a href="{url mod=pCatalog act=default task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></div>
            {/if}            
        {/foreach}
       
       </div>   
    {/if}
</div>       