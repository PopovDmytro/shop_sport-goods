 {strip}
    <div class="heading-block" id="cotalog_headers">
        <ul class="breadcrumbs">
            <li><a href="{url mod=home}">Главная</a></li>
            {strip}
                <li><strong class="lider">{$pcatalog->name}</strong></li>
                {if $brandName}
                    <li><strong class="lider">{$brandName}</strong></li>
                {/if}
            {/strip}
        </ul>
        <a href="javascript:void(0);" id="models" class="view-list{if !$modal} active{/if}"></a>
        <a href="javascript:void(0);" id="models" class="view-plate{if $modal} active{/if}"></a>
    </div>
     <form class="content-filters" id="filter-form" action="{url route=shop_fsearch task=$pcatalog->alias uri=''}"
         name="filter-form" method="get">
         {include file="brand/list.inc.tpl"} {*производитель*}
         <div class="holder three-fourth">
             {include file="pcatalog/filter_price.inc.tpl"}
         </div>
         {*{if $filter_form and !$instant_filter}*}
         {*<center><input type="submit" value="Подобрать" class="a-button"></center>*}
         {*{else}*}
         {*<script language="javascript">*}
         {*{literal}*}
         {*$('#filter-form input:not([type=submit])').unbind('change').bind('change', function(event){*}
         {*//                        $('#filter-form').submit();*}
         {*});*}
         {*{/literal}*}
         {*</script>*}
         {*{/if}*}
         {if $filter_form}
             {*аттрибуты*}
             <div class="holder full-w">
                 {include file="attribute/filter_form.inc.tpl"}
             </div>
         {/if}
         {include file="attribute/selected.tpl"}
     </form>
     <div id="levels-contents">
         {*{if $modal}*}
             {*{include file="pcatalog/product.block.level.inc.tpl"}*}
         {*{else}*}
             {*{include file="pcatalog/product.list.level.inc.tpl"}*}
         {*{/if}*}
     </div>

    {if $pager_count > 1}
        <div class="pagination">
            <a href="javascript:void(0);" class="back"></a>
            <ul>
                {foreach from=$pager->pages key=key item=item}
                    {if $pager->currentPage eq $item}
                        <li class="pagination_div active">
                            <b>{$item}</b>
                        </li>
                    {elseif $item eq 1}
                        {if $brand_alias}
                            <li class="pagin-item"><a href="{url mod=pCatalog act=default brand_alias=$brand_alias  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {else}
                            <li class="pagin-item"><a href="{url mod=pCatalog act=default  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {/if}
                    {else}
                        {if $brand_alias}
                            <li class="pagin-item"><a href="{url mod=pCatalog act=default brand_alias=$brand_alias task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {else}
                        <li class="pagin-item"><a href="{url mod=pCatalog act=default task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {/if}
                    {/if}            
                {/foreach}
            </ul>
            <a href="javascript:void(0);" class="forward"></a>
        </div>
    {/if}                        
    
    <div id="products-contents">
        {if $modal}
            {include file="pcatalog/product.block.tpl"}
        {else}
            {include file="pcatalog/product.list.tpl"}
        {/if}
        
    </div>
    <div class="clear"></div>
    {if $pager_count > 1}
        <div class="pagination">
            <a href="javascript:void(0);" class="back"></a>
            <ul>
                {foreach from=$pager->pages key=key item=item}
                    {if $pager->currentPage eq $item}
                        <li class="pagination_div active">
                            <b>{$item}</b>
                        </li>
                    {elseif $item eq 1}
                        {if $brand_alias}
                            <li class="pagin-item"><a href="{url mod=pCatalog act=default brand_alias=$brand_alias  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {else}
                            <li class="pagin-item"><a href="{url mod=pCatalog act=default  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {/if}
                    {else}
                        {if $brand_alias}
                            <li class="pagin-item"><a href="{url mod=pCatalog act=default brand_alias=$brand_alias task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {else}
                        <li class="pagin-item"><a href="{url mod=pCatalog act=default task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                        {/if}
                    {/if}            
                {/foreach}
            </ul>
            <a href="javascript:void(0);" class="forward"></a>
        </div>
        {if "ajax_paging"|settings == "true"}
            <div class="ajax-paging view-more">Показать еще {"per_page"|settings}</div>
        {/if} 
    {/if}
    {if !$brand_content2 and $pager->currentPage eq 1}
            <div class="brand_content">{$pcatalog->content}</div>
    {/if}
    {if $uri}
        <div class="brand_content">{$brand_content}</div>
    {/if}
    <div class="brand_content">{$brand_content2}</div>
     <script>
         $(document).ready(function(){
             /*var menu_min_category = $('#cat-list').find('#menu-n'+'{$pcatalog->id}');   */
             var menu_category = $('.sidebar-holder').find('#menu-n'+'{$pcatalog->id}');
             /*menu_min_category.addClass('active').show();
             menu_min_category.closest('ul').show().children().show();
             menu_min_category.siblings('ul').show().children().show(); */
             menu_category.addClass('active');
             menu_category.closest('ul').slideDown();
             /*menu_category.siblings('ul').show().children().show();       */
             if ('{$pcatalog->level}' > 1) {
                 $(menu_category.closest('ul')).closest('ul.level1').show().children('li').show();
             }
         });
     </script>   
 {/strip}