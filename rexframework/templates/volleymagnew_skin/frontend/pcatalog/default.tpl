{strip}
    <div class="breadcrumbs-block row">
        <div class="columns small-12">
            <ul class="breadcrumbs_list no-bullet">
                <li class="breadcrumbs_item">
                    <a href="{url mod=home}" class="breadcrumbs_link">
                        <i aria-hidden="true" class="fa fa-home"></i>Главная
                    </a>
                </li>
                {strip}
                    {if $pcatalog->name}
                    <li class="breadcrumbs_item active">
                        <a href="javascript:void(0)" class="breadcrumbs_link">{$pcatalog->name}</a>
                    </li>
                    {/if}
                    {if $brandName}
                        <li class="breadcrumbs_item active">
                            <a href="javascript:void(0)" class="breadcrumbs_link">{$brandName}</a>
                        </li>
                    {/if}
                {/strip}
            </ul>
        </div>
    </div>
    <div class="categories_wrapper">
        <section class="row">
            {*filter block*}
            <div class="columns xlarge-3 large-4 medium-5 small-12">
                <form class="content-filters" id="filter-form" action="{url route=shop_fsearch task=$pcatalog->alias uri=''}" name="filter-form" method="get">
                    <aside class="categories_sidebar">
                        <ul data-accordion class="accordion">
                            {*brands*}
                            {include file="brand/list.inc.tpl"}
                            {**}
                            {*price*}
                            {include file="pcatalog/filter_price.inc.tpl"}
                            {**}
                            {*filter size, sex*}
                            {include file="attribute/filter_form.inc.tpl"}
                            {**}
                        </ul>
                        {*<script type="text/javascript">
                            {literal}
                            $(document).ready(function(){
                                $('.accordion-title').on('click', function(e){
                                    $(this).parent().toggleClass('is-active');
                                    $(this).siblings('.accordion-content').slideToggle();
                                });
                            });
                            {/literal}
                        </script>*}
                    </aside>
                    <div class="categories_form-ctrls">
                        {include file="attribute/selected.tpl"}
                    </div>
                </form>
            </div>
            {**}
            {*products list / grid*}
            <div class="columns large-8 xlarge-offset-1 medium-7 small-12">
                {*pagination*}
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
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default brand_alias=$brand_alias  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {else}
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {/if}
                                {else}
                                    {if $brand_alias}
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default brand_alias=$brand_alias task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {else}
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {/if}
                                {/if}
                            {/foreach}
                        </ul>
                        <a href="javascript:void(0);" class="forward"></a>
                    </div>
                {/if}
                <div class="categories_products">
                    <div class="row xlarge-up-3 large-up-2 small-up-1 categories_products-list" id="products-contents">
                        {include file="pcatalog/product.block.tpl"}
                    </div>
                </div>
                {*pagination / view more*}
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
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default brand_alias=$brand_alias  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {else}
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default  task=$pcatalog->alias}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {/if}
                                {else}
                                    {if $brand_alias}
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default brand_alias=$brand_alias task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {else}
                                        <li class="pagin-item">
                                            <a href="{url mod=pCatalog act=default task=$pcatalog->alias}{$item}/{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a>
                                        </li>
                                    {/if}
                                {/if}
                            {/foreach}
                        </ul>
                        <a href="javascript:void(0);" class="forward"></a>
                    </div>
                    {if "ajax_paging"|settings == "true"}
                        <div class="row">
                            <div class="columns small-12 text-center">
                                <button type="button" class="ajax-paging common_link-btn btn btn--blue">Показать еще {"per_page"|settings}</button>
                            </div>
                        </div>
                    {/if}
                {/if}
                {if !$brand_content2 and $pager->currentPage eq 1}
                    <div class="brand_content">{$pcatalog->content}</div>
                {/if}
                {if $uri}
                    <div class="brand_content">{$brand_content}</div>
                {/if}
                <div class="brand_content">{$brand_content2}</div>
                {*TODO making aktive category menu item*}
                <script>
                    $(document).ready(function () {
                        /*var menu_min_category = $('#cat-list').find('#menu-n'+'{$pcatalog->id}');   */
                        var menu_category = $('.sidebar-holder').find('#menu-n' + '{$pcatalog->id}');
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
                {**}
                {**}
            </div>
            {**}
        </section>
    </div>
    <div id="levels-contents"></div>
{/strip}

{include file="product/last.tpl"}