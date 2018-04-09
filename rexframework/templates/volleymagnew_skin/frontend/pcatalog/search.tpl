<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">Поиск: {$q}</a>
            </li>
        </ul>
    </div>
</div>


<div id="products-contents">
    {if $notFound}
        <h2>Поиск не дал результатов. Попробуйте изменить запрос.</h2>
    {/if}
    {if $modal}
        {include file="pcatalog/product.block.tpl"}
    {else}
        {include file="pcatalog/product.list.tpl"}
    {/if}
</div>
<div class="clear"></div>
{if $pager_count gt 1}
<input type="hidden" id="count_next" value="{$count_next}">

    <div class="pagination">
        <a href="javascript:void(0);" class="back"></a>
        <ul>
            {foreach from=$pager->pages key=key item=item}
                {if $pager->currentPage eq $item}
                    <li class="pagination_div active">
                        <b>{$item}</b>
                    </li>
                {elseif $item eq 1}
                    <li class="pagin-item"><a href="{url route=shop_search_one q=$q}">{$item}</a></li>
                {else}
                    <li class="pagin-item"><a href="{url mod=pCatalog act=search q=$q page=$item}">{$item}</a></li>
                {/if}
            {/foreach}
        </ul>
        <a href="javascript:void(0);" class="forward"></a>
    </div>
    {if "ajax_paging"|settings == "true"}
        <div class="ajax-paging view-more">Показать еще {"per_page"|settings}</div>
    {/if}
{/if}
