<ul class="product-list">
    {assign var=iteration value=0}
    {foreach from=$bestseller_productList key=featured_key item=featured name=product_list}
        {assign var=featured_id value=$featured.id}
        {if $bestseller_brandList.$featured_id}
            {assign var=featuredBrand value=$bestseller_brandList.$featured_id}
        {/if}
        {assign var=featuredCategory value=$bestseller_categoryList.$featured_id}
        {assign var=feaImageColor value=$bestseller_color.$featured_id}
        <li class="parent-list">
            <div class="img-box">
                <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$featured_id}" class="wrapper">
                    {if $bestseller_imageList.$featured_id}
                        {assign var=image value=$bestseller_imageList.$featured_id}
                        {if $feaImageColor}
                            <img src="{getimg type=list name=pImage id=$feaImageColor.0.img_id ext=$feaImageColor.0.img_ext}"/>
                        {else}
                            <img src="{getimg type=list name=pImage id=$image.id ext=$image.image}"/>
                        {/if}
                    {else}
                        {img width="300" height="300" src="list.jpg" class="t-image"}
                    {/if}
                </a>
            </div>
            <div class="info-product">
                <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$featured_id}" class="heading-product">{$featured.name}</a>
                <div class="info-box">
                    <div class="container">
                        <div class="parameters">
                            <a class="catalog_uri" href="{url mod=pCatalog act=default task=$featuredCategory->alias}">{$featuredCategory->name}</a>
                            {if count($feaImageColor)}
                                <span>
                                    <span>Артикул:</span>
                                    <strong class="sku-article">{$featured_id}</strong>
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
                                {if $featured.sale}
                                    <div class="price-sale">
                                            <span class="prise-sale-full">{$featured.price} грн</span>
                                            <span class="prise-tosale cost">{($featured.price - $featured.price*$featured.sale/100)|round:0} грн</span>
                                    </div>
                                {else}
                                       <span class="prise-tosale cost">{$featured.price} грн</span>
                                {/if}
                            </div>
                            {if count($feaImageColor)}
                                <div class="wrapper-buy">
                                    <form action="" method="post" id="cartForm{$featured.id}" class="product-def-form">
                                        <input type="hidden" name="mod" value="cart">
                                        <input type="hidden" name="act" value="add">
                                        <input type="hidden" name="cart[sku]" value="">
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
                            <a href="{url mod=product act=default cat_alias=$featuredCategory->alias task=$featured_id}" class="more">Подробнее</a>
                        </div>
                    </div>
                    {if $feaImageColor}
                        <div class="photo-block" {if $feaImageColor|count < 6}id="nosliders" {/if}>
                            <ul class="photo-list slider6">
                                {foreach from=$feaImageColor item=item}
                                    <li class="slide">
                                        <div class="wrapper">
                                            <a href="javascript:void(0);" {if isset($colorProductCart["`$featured_id`:1:`$item.value`"])}class="attr--incart"{/if} {if $item.img_id}attr_src="{getimg type=list name=pImage id=$item.img_id ext=$item.img_ext}"{/if} data-attr_value="{$item.value}" data-sku_article="{$item.sku_article}" attr_id="{$item.attribute_id}" id="{$item.id}" data-price="{$item.price}" data-fullprice="{$item.price}" data-lastprice="{if $featured.sale eq 0}{$item.price}{else}{($item.price - $item.price*$featured.sale/100)|floor}{/if}">
                                                {if $item.img_id}
                                                    <img src="{getimg type=icon name=pImage id=$item.img_id ext=$item.img_ext}"/>
                                                {else}
                                                    {$item.name}
                                                {/if}
                                            </a>
                                        </div>
                                    </li>
                                {/foreach}
                            </ul>
                        </div>
                    {/if}
                </div>
            </div>
        </li>
    {/foreach}
</ul>
<!--<script type="text/javascript">
{literal}
$(document).ready(function(){
         $('.bx-next').die('click').live('click', function(){

                $(this).css();

        });
        });

{/literal}
</script>-->