{assign var=iteration value=0}
<input type="hidden" id="count_next" value="{$count_next}">
{*<h1>
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
</h1>*}
<div class="my-slider my-slider_product">
{foreach from=$productList key=key item=featured name=product_list}
    {assign var=product_id value=$featured.id}
    {assign var=product_name value=$featured.name}
    {assign var=currBrand value=$brandList.$product_id}
    {assign var=featuredCategory value=$categoryList.$product_id}
    {assign var=prodColoritem value=$prodColor.$product_id}
        <div class="my-sliders_slide product_slide">
            <div class="product_entity">
                <a href="{url mod=product act=default task=$product_id cat_alias=$featuredCategory.alias}" class="block-link">
                    <div class="img-holder">
                        {if $imageList.$product_id}
                            {assign var=image value=$imageList.$product_id}
                            {if $prodColoritem.0.img_id and $prodColoritem.0.img_ext}
                                <img width="240" height="240" class="slide-img {$imageColor}" src="{getimg type=listblock name=pImage id=$prodColoritem.0.img_id ext=$prodColoritem.0.img_ext}"/>
                            {else}
                                <img src="{getimg type=listblock name=pImage id=$image.id ext=$image.image}"/>
                            {/if}
                        {else}
                            {img src="page.jpg"}
                        {/if}
                    </div>
                    <div class="product_price-holder">
                        {if $featured.sale neq 0}
                            <span class="product-price product-price--regular">{$featured.price|floor} грн</span>
                            <span class="product-price product-price--discount">{($featured.price - $featured.price*$featured.sale/100)|floor} грн</span>
                        {else}
                            <span class="product-price product-price--regular">{$featured.price|floor} грн</span>
                        {/if}
                    </div>
                    <div class="product_title">
                        <p class="title">{$product_name}</p>
                    </div>
                </a>
                <form action="" method="post" id="cartForm{$featured.id}">
                    <div class="product_btns-holder">
                        <input type="hidden" name="mod" value="cart">
                        <input type="hidden" name="act" value="add">
                        <input type="hidden" name="cart[sku]" value="{$prodColoritem.0.skus_id}">
                        <input type="hidden" name="cart[1]" value="">
                        <input type="hidden" name="cart[product_id]" value="{$featured.id}">
                        <input type="hidden" name="cart[submit]" value="Купить">
                        <input type="hidden" name="cart[count]" id="info-product-count" value="1" />

                        <button type="button" class="product-btn product-btn--cart">
                            <span class="text-holder">
                                {img src='main-page/cart_icon.png' class='btn-icon cart-icon'}В корзину
                            </span>
                        </button>
                        <a href="javascript:void(0)" pid="{$featured.id}" class="product-btn product-btn--compare compare_{$featured.id} {if $isset_compare}comp-added{/if}">
                            {img src='main-page/compare_icon.png' class='compare-icon'}
                        </a>
                    </div>
                </form>

                {*TODO thumb images on product item*}
                    {*{if $prodColoritem|count neq 1}
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
                    {/if}*}
                {*---*}
            </div>
        </div>
{/foreach}
</div>