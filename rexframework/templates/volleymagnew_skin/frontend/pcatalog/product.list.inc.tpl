{foreach from=$productList key=key item=featured name=product_list}
    {assign var=product_id value=$featured.id}
    {assign var=product_name value=$featured.name}
    {assign var=currBrand value=$brandList.$product_id}
    {assign var=featuredCategory value=$categoryList.$product_id}
    {assign var=prodColoritem value=$prodColor.$product_id}
    <div class="column product_slide">
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

                    <a href="javascript:void(0);" class="product-btn product-btn--cart button-cart buy">
                            <span class="text-holder">
                                {img src='main-page/cart_icon.png' class='btn-icon cart-icon'}В корзину
                            </span>
                    </a>
                    <a href="javascript:void(0)" pid="{$featured.id}" class="product-btn product-btn--compare pa_compare compare_{$featured.id} compare button-cart-active {if $isset_compare}comp-added{/if}">
                        {img src='main-page/compare_icon.png' class='compare-icon'}
                    </a>
                </div>
            </form>
        </div>
    </div>
{/foreach}
