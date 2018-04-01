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
    {if !$currBrand}
         {assign var=currBrand value=$brandList.$featured.brand_id}
    {/if}
    {assign var=featuredCategory value=$categoryList.$product_id}
    {assign var=prodColoritem value=$prodColor.$product_id}

        <li class="parent-list">
            <div class="img-box">
                <a href="{url mod=product act=default task=$product_id cat_alias=$featuredCategory.alias}" class="wrapper">
                    {if $imageList.$product_id}
                        {assign var=image value=$imageList.$product_id}
                        {if $prodColoritem.0.img_id and $prodColoritem.0.img_ext}
                            <img class="one{$imageColor}" src="{getimg type=list name=pImage id=$prodColoritem.0.img_id ext=$prodColoritem.0.img_ext}"/>
                        {else}
                            <img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>
                        {/if}
                    {else}
                        {img width="300" height="300" src="list.jpg" class="t-image"}
                    {/if}
                </a>
            </div>
             <div class="info-product">
                <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$product_id}" class="heading-product">{$product_name}</a>
                <div class="info-box">
                    <div class="container">
                        <div class="parameters">
                            <!--<a class="heading-product" href="{url mod=pCatalog act=default task=$currBrand->alias}">{$currBrand->name}</a>-->
                            <a class="heading-product" href="{url route="shop_fsearch" task=$featuredCategory->alias uri="?filter[brand][]="}{$currBrand->id}">{$currBrand->name}</a>
                            {if count($prodColoritem)}
                                <span>
                                    <span>Артикул:</span>
                                    <strong class="sku-article">{$product_id}</strong>
                                </span>
                            {/if}
                            {if $featured.weight}
                                <span><span>Вес:</span><strong>{$featured.weight} гр</strong></span>
                            {/if}
                            {if $featured.unit}
                                <span><span>Единица:</span><strong>{$featured.unit}</strong></span>
                            {/if}
                            {if $featured.sex}
                                <span><span>Пол:</span><strong>{$featured.sex}</strong></span>
                            {/if}
                        </div>
                        <div class="buy-block">
                            <div class="cost-block">
                                {if $featured.sale neq 0 }
                                    <div class="price-sale">
                                        <span class="prise-sale-full">{$featured.price|floor} грн</span>
                                        <span class="prise-tosale cost">{($featured.price - $featured.price*$featured.sale/100)|floor} грн</span>
                                    </div>
                                {else}
                                    <span class="prise-tosale cost">{$featured.price|floor} грн </span>
                                {/if}
                            </div>
                            {if count($prodColoritem)}
                                <div class="wrapper-buy">
                                    <form action="" method="post" id="cartForm{$featured.id}" class="product-def-form">
                                        <input type="hidden" name="mod" value="cart">
                                        <input type="hidden" name="act" value="add">
                                        <input type="hidden" name="cart[sku]" value="{$skuList.$product_id}">
                                        <input type="hidden" name="cart[1]" value="">
                                        <input type="hidden" name="cart[product_id]" value="{$featured.id}">
                                        <input type="hidden" name="cart[submit]" value="Купить">
                                        <input type="hidden" name="cart[count]" id="info-product-count" value="1" />
                                        <a href="javascript:void(0);" class="button-cart buy">В корзину</a>
                                        <a href="javascript:void(0);" pid="{$featured.id}" class="pa_compare compare_{$featured.id} compare button-cart-active {if $isset_compare}comp-added{/if}"></a>
                                    </form>
                                </div>
                            {else}
                                Нет в наличии
                            {/if}
                            <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$product_id}" class="more">Подробнее</a>
                        </div>
                    </div>
                    {if $prodColoritem}
                        <div class="photo-block" {if $prodColoritem|count < 6}id="nosliders" {/if}>
                            <ul class="photo-list slider6">
                                {if count($prodColoritem)}

                                    {foreach from=$prodColoritem item=item}
                                        <li class="slide">
                                            <div class="wrapper">
                                                <a href="javascript:void(0);" {if isset($colorProductCart["`$product_id`:1:`$item.value`"])}class="attr--incart"{/if} {if $item.img_id}attr_src="{getimg type=list name=pImage id=$item.img_id ext=$item.img_ext}"{/if} data-attr_value="{$item.value}" data-sku_article="{$item.sku_article}" data-skus_id="{$item.skus_id}" attr_id="{$item.attribute_id}" id="{$item.id}" data-fullprice="{$item.price}" data-lastprice="{if $item.sale eq 0}{$item.price}{else}{($item.price - $item.price*$item.sale/100)|floor}{/if}">
                                                    {if $item.img_id}
                                                        <img src="{getimg type=icon name=pImage id=$item.img_id ext=$item.img_ext}"/>
                                                    {else}
                                                        {$item.name}
                                                    {/if}
                                                </a>
                                            </div>
                                        </li>
                                    {/foreach}

                                {/if}
                            </ul>
                        </div>
                    {/if}
                </div>
            </div>
        </li>
{/foreach}
