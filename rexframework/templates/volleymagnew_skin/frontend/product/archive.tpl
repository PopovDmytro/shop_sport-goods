<div class="breadcrumbs-block row">
    <div class="columns small-12">
        <ul class="breadcrumbs_list no-bullet">
            <li class="breadcrumbs_item">
                <a href="{url mod=home}" class="breadcrumbs_link">
                    <i aria-hidden="true" class="fa fa-home"></i>Главная
                </a>
            </li>
            {strip}
            <li class="breadcrumbs_item active">
                <a href="javascript:void(0)" class="breadcrumbs_link">{$name_feath}</a>
            </li>
            {/strip}
        </ul>
    </div>
</div>



<div class="category">
    <div class="heading-block" id="cotalog_headers">
        <a href="javascript:void(0);" id="models" class="view-list{if !$modal} active{/if}"></a>
        <a href="javascript:void(0);" id="models" class="view-plate{if $modal} active{/if}"></a>
    </div>
  
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
                        <li class="pagin-item"><a href="{url mod=product act=archive feature=$feature task=$item}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                    {else}
                        <li class="pagin-item"><a href="{url mod=product act=archive feature=$feature task=$item}{if $parseUrl.query}filter/?{$parseUrl.query}{/if}">{$item}</a></li>
                    {/if}            
                {/foreach}
            </ul>
            <a href="javascript:void(0);" class="forward"></a>
        </div>
        {if "ajax_paging"|settings == "true"}
            <div class="ajax-paging view-more">Показать еще {"per_page"|settings}</div>
        {/if}
    {/if}
</div>