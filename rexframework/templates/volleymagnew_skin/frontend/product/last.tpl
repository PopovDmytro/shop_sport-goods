{if $lastproduct}
<section class="products-section">
    <div class="row align-center">
        <div class="column small-12">
            <h1 class="section-title section-title--blue">Последние просмотренные</h1>
            <div class="my-slider my-slider_product">
                {foreach from=$lastproduct key=key item=item}
                    <div class="column product_slide">
                        <div class="product_entity">
                            <a href="{url mod=product act=default task=$item.id cat_alias=$item.palias}" class="block-link">
                                <div class="img-holder">
                                    {if $item.pimageid}
                                        <img width="240" height="240" class="slide-img {$imageColor}" src="{getimg type=list name=pImage id=$item.pimageid ext=$item.image}"/>
                                    {else}
                                        {img src="page.jpg"}
                                    {/if}
                                </div>
                                <div class="product_price-holder">
                                    {if $item.sale}
                                        {assign var=new value=$item.price -$item.price*$item.sale/100}
                                        <span class="product-price product-price--discount">{$new|floor } грн</span>
                                        <span class="product-price product-price--regular">{$item.price} грн</span>
                                    {else}
                                        <span class="product-price product-price--regular">{$item.price} грн</span>
                                    {/if}
                                </div>
                                <div class="product_title">
                                    <p class="title">{$item.name}</p>
                                </div>
                                {*{$item.content|truncate:200}*}
                            </a>
                            <form action="" method="post" id="cartForm{$item.id}">
                                <div class="product_btns-holder">
                                    <input type="hidden" name="mod" value="cart">
                                    <input type="hidden" name="act" value="add">
                                    {*<input type="hidden" name="cart[sku]" value="{$prodColoritem.0.skus_id}">*}
                                    <input type="hidden" name="cart[1]" value="">
                                    <input type="hidden" name="cart[product_id]" value="{$item.id}">
                                    <input type="hidden" name="cart[submit]" value="Купить">
                                    <input type="hidden" name="cart[count]" id="info-product-count" value="1" />

                                    <a href="javascript:void(0);" class="product-btn product-btn--cart button-cart buy">
                            <span class="text-holder">
                                {img src='main-page/cart_icon.png' class='btn-icon cart-icon'}В корзину
                            </span>
                                    </a>
                                    <a href="javascript:void(0)" pid="{$item.id}" class="product-btn product-btn--compare pa_compare compare_{$item.id} compare button-cart-active {if $isset_compare}comp-added{/if}">
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
        </div>
    </div>
</section>
{/if}

