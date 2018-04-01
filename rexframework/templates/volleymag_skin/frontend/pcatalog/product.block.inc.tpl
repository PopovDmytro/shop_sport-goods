{assign var=iteration value=0}
<input type="hidden" id="count_next" value="{$count_next}">
<h1>
    {if $task eq 'nakolenniki' and $brand_alias eq 'mizuno'}
        Наколенники Mizuno
    {elseif $task eq 'mjachi' and $brand_alias eq 'mikasa'}
        Мячи Mikasa
    {elseif $task eq 'mjachi' and $brand_alias eq 'select'}
        Футзальные и футбольные мячи Select
    {elseif $task eq 'obyv-dlja-bega' and $brand_alias eq 'asics'}
        Кроссовки Asics для бега
    {elseif $task eq 'obyv-dlja-bega' and $brand_alias eq 'mizuno'}
        Кроссовки Mizuno для бега
    {else}
        {if $pcatalog->name_single}{$pcatalog->name_single}{else}{$pcatalog->name}{/if}
    {/if}
</h1>
{foreach from=$productList key=key item=featured name=product_list}
    {assign var=product_id value=$featured.id}
    {assign var=product_name value=$featured.name}
    {assign var=currBrand value=$brandList.$product_id}
    {assign var=featuredCategory value=$categoryList.$product_id}
    {assign var=prodColoritem value=$prodColor.$product_id}
        <li class="parent-list">
            <a href="{url mod=product act=default task=$product_id cat_alias=$featuredCategory.alias}">
                <div class="img-box">
                    <div class="wrapper">
                        {if $imageList.$product_id}
                        {assign var=image value=$imageList.$product_id}
                            {if $prodColoritem.0.img_id and $prodColoritem.0.img_ext}
                                <img class="one{$imageColor}" src="{getimg type=listblock name=pImage id=$prodColoritem.0.img_id ext=$prodColoritem.0.img_ext}"/>
                            {else}
                                <img src="{getimg type=listblock name=pImage id=$image.id ext=$image.image}"/>
                            {/if}
                        {else}
                            {img width="240" height="240" src="page.jpg" class="t-image"}
                        {/if}
                    </div>
                </div>
                <div class="info-block">
                {*<div class="category">{$pcatalog->name}</div>*}
                    <div class="cost-block">
                        {if $featured.sale neq 0}
                            <div class="price-sale">
                                <span class="prise-sale-full" style="float:left">{$featured.price|floor} грн</span>
                                <span class="prise-tosale cost" style="color: #EA4556; font-size: 27px; line-height: 27px; float: right;">{($featured.price - $featured.price*$featured.sale/100)|floor} грн</span>
                            </div>
                        {else}
                            <span class="prise-tosale cost">{$featured.price|floor} грн </span>
                        {/if}
                    </div>
                    <span class="info-product">{$product_name}</span>
                </div>
            </a>
            {if $prodColoritem|count neq 1}
                <ul class="list-photo">
                    {assign var=countimg value=0}
                    {foreach from=$prodColoritem item=item}
                        {if $item.img_id && $countimg neq 5}
                            <li>
                                <a href="javascript:void(0);" {if isset($colorProductCart["`$product_id`:1:`$item.value`"])}class="attr--incart"{/if} attr_src="{getimg type=listblock name=pImage id=$item.img_id ext=$item.img_ext}" data-attr_value="{$item.value}" data-sku_article="{$item.sku_article}" data-skus_id="{$item.skus_id}" attr_id="{$item.attribute_id}"  data-price="{if $item.sale}{($item.price - $item.price*$item.sale/100)|floor}{else}{$item.price}{/if}" id="{$item.id}">
                                    <img src="{getimg type=minis name=pImage id=$item.img_id ext=$item.img_ext}"/>
                                </a>
                            </li>
                            {assign var=countimg value=$countimg+1}
                        {else}
                            <li class="nocolor">
                                <div>
                                    <a href="javascript:void(0);" {if isset($colorProductCart["`$product_id`:1:`$item.value`"])}class="attr--incart"{/if} data-attr_value="{$item.value}" data-sku_article="{$item.sku_article}" attr_id="{$item.attribute_id}" id="{$item.id}" data-price="{if $item.sale}{($item.price - $item.price*$item.sale/100)|floor}{else}{$item.price}{/if}" id="{$item.id}" >
                                        {$item.name|truncate:9}
                                    </a>
                                </div>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            {/if}
            <div class="buy-block">
                <form action="" method="post" id="cartForm{$featured.id}" class="product-def-form">
                    <input type="hidden" name="mod" value="cart">
                    <input type="hidden" name="act" value="add">
                    <input type="hidden" name="cart[sku]" value="{$prodColoritem.0.skus_id}">
                    <input type="hidden" name="cart[1]" value="">
                    <input type="hidden" name="cart[product_id]" value="{$featured.id}">
                    <input type="hidden" name="cart[submit]" value="Купить">
                    <input type="hidden" name="cart[count]" id="info-product-count" value="1" />
                    <a href="javascript:void(0);" class="button-cart buy">В корзину</a>
                    <a href="javascript:void(0);" pid="{$featured.id}" class="pa_compare compare_{$featured.id} compare button-cart-active {if $isset_compare}comp-added{/if}"></a>
                </form>
            </div>
            {*<a href="{url mod=pCatalog act=default task=$pcatalog->alias}" class="category">{$pcatalog->name}</a>*}
        </li>
{/foreach}